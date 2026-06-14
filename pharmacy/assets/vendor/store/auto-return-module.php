<?php
// Auto Return Module - Returns items to store after 24 hours if not confirmed/rejected

class AutoReturnModule {
    private $connection;
    
    public function __construct($conn) {
        $this->connection = $conn;
    }
    
    /**
     * Check for expired releases and return them to store
     */
    public function processExpiredReleases() {
        try {
            // Find all releases that are older than 24 hours and still pending
            $query = "SELECT r.id, r.item_id, r.quantity_released, r.release_date, 
                            s.total_remaining_quantity 
                     FROM receivingbay r 
                     INNER JOIN store s ON r.item_id = s.item_id 
                     WHERE r.status = 'pending' 
                     AND TIMESTAMPDIFF(HOUR, r.release_date, NOW()) >= 24";
            
            $result = mysqli_query($this->connection, $query);
            
            if (!$result) {
                throw new Exception("Error fetching expired releases: " . mysqli_error($this->connection));
            }
            
            $expiredReleases = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $expiredReleases[] = $row;
            }
            
            // Process each expired release
            foreach ($expiredReleases as $release) {
                $this->returnItemToStore($release);
            }
            
            return [
                'success' => true,
                'processed_count' => count($expiredReleases),
                'message' => count($expiredReleases) . ' expired releases processed successfully'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Return a specific item to store
     */
    private function returnItemToStore($release) {
        // Start transaction
        mysqli_autocommit($this->connection, false);
        
        try {
            // Update receivingbay - mark as returned
            $updateReceivingQuery = "UPDATE receivingbay 
                                   SET status = 'auto_returned', 
                                       return_date = NOW(),
                                       notes = CONCAT(IFNULL(notes, ''), ' - Auto returned after 24 hours')
                                   WHERE id = " . intval($release['id']);
            
            $receivingResult = mysqli_query($this->connection, $updateReceivingQuery);
            
            if (!$receivingResult) {
                throw new Exception("Failed to update receivingbay: " . mysqli_error($this->connection));
            }
            
            // Update store - add quantity back
            $newTotalQuantity = $release['total_remaining_quantity'] + $release['quantity_released'];
            $updateStoreQuery = "UPDATE store 
                               SET total_remaining_quantity = " . intval($newTotalQuantity) . ",
                                   last_updated = NOW()
                               WHERE item_id = " . intval($release['item_id']);
            
            $storeResult = mysqli_query($this->connection, $updateStoreQuery);
            
            if (!$storeResult) {
                throw new Exception("Failed to update store: " . mysqli_error($this->connection));
            }
            
            // Log the auto return action
            $this->logAutoReturn($release);
            
            // Commit transaction
            mysqli_commit($this->connection);
            
        } catch (Exception $e) {
            // Rollback on error
            mysqli_rollback($this->connection);
            throw $e;
        } finally {
            // Restore autocommit
            mysqli_autocommit($this->connection, true);
        }
    }
    
    /**
     * Log the auto return action
     */
    private function logAutoReturn($release) {
        $logQuery = "INSERT INTO auto_return_log 
                    (item_id, quantity_returned, original_release_date, return_date, reason)
                    VALUES (
                        " . intval($release['item_id']) . ",
                        " . intval($release['quantity_released']) . ",
                        '" . mysqli_real_escape_string($this->connection, $release['release_date']) . "',
                        NOW(),
                        'Auto returned - 24 hour timeout'
                    )";
        
        mysqli_query($this->connection, $logQuery);
    }
    
    /**
     * Get statistics about auto returns
     */
    public function getAutoReturnStats($days = 30) {
        $query = "SELECT 
                    COUNT(*) as total_auto_returns,
                    SUM(quantity_returned) as total_quantity_returned,
                    DATE(return_date) as return_date
                  FROM auto_return_log 
                  WHERE return_date >= DATE_SUB(NOW(), INTERVAL " . intval($days) . " DAY)
                  GROUP BY DATE(return_date)
                  ORDER BY return_date DESC";
        
        $result = mysqli_query($this->connection, $query);
        
        $stats = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $stats[] = $row;
            }
        }
        
        return $stats;
    }
    
    /**
     * Manual check for items about to expire (within next 2 hours)
     */
    public function getItemsAboutToExpire() {
        $query = "SELECT r.id, r.item_id, r.quantity_released, r.release_date,
                        s.item_name, s.total_remaining_quantity,
                        TIMESTAMPDIFF(HOUR, r.release_date, NOW()) as hours_elapsed
                 FROM receivingbay r 
                 INNER JOIN store s ON r.item_id = s.item_id 
                 WHERE r.status = 'pending' 
                 AND TIMESTAMPDIFF(HOUR, r.release_date, NOW()) >= 22
                 AND TIMESTAMPDIFF(HOUR, r.release_date, NOW()) < 24
                 ORDER BY r.release_date ASC";
        
        $result = mysqli_query($this->connection, $query);
        
        $items = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $items[] = $row;
            }
        }
        
        return $items;
    }
}

include("include/connect.php");

// Initialize the auto return module with your $conn variable
$autoReturn = new AutoReturnModule($conn);

// If this script is called directly, process expired releases
if (basename(__FILE__) == basename($_SERVER['SCRIPT_NAME'])) {
    $result = $autoReturn->processExpiredReleases();
    
    header('Content-Type: application/json');
    echo json_encode($result);
}
?>
