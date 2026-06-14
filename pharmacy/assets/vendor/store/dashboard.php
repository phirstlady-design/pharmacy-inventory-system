<?php
include("include/connect.php");
include("include/validate.php");
session_start();

if (!isset($_SESSION['store_section']) || empty($_SESSION['store_section'])) {
    header('Location: login.php'); // Change to your actual login page
    exit();
}
$store_section = $_SESSION['store_section'];

// Define the common dashboard links
$common_links = [
    'Home' => ['link' => 'dashboard.php', 'icon' => 'fas fa-home'],
    // 'Logout' => ['link' => 'logout.php', 'icon' => 'fas fa-arrow-up'],
];

// Define store-specific links
$store_links = [
    'medicalStore' => [
        ['label' => 'medical Preview Page', 'link' => 'medStore.php', 'icon' => 'fas fa-box'],
        // ['label' => 'Request Item Page', 'link' => 'requisitionform.php', 'icon' => 'fas fa-arrow-up'],
        ['label' => 'Item Confirmation Page', 'link' => 'medconfirm.php', 'icon' => 'fas fa-book'],
        ['label' => 'Report', 'link' => 'receivingbayreport.php', 'icon' => 'fas fa-book'],
        ['label' => 'Report page', 'link' => 'storereport.php', 'icon' => 'fas fa-book'],
    ],
    'hardwareStore' => [
        ['label' => 'Hardware Page', 'link' => 'hardwareStore.php', 'icon' => 'fas fa-home'],
        // ['label' => 'Request Item Page', 'link' => 'requisitionform.php', 'icon' => 'fas fa-arrow-up'],
        ['label' => 'Item Confirmation Page', 'link' => 'hardwareconfirm.php', 'icon' => 'fas fa-book'],
        ['label' => 'Report', 'link' => 'receivingbayreport.php', 'icon' => 'fas fa-book'],
        ['label' => 'Report page', 'link' => 'storereport.php', 'icon' => 'fas fa-book'],
    ],

    'generalStationeryStore' => [
        ['label' => ' General Stationery Page', 'link' => 'stationeryStore.php', 'icon' => 'fas- fa-box'],
        ['label' => ' Health Stationery Page', 'link' => 'healthstationeryStore.php', 'icon' => 'fas fa-box'],
        ['label' => 'Item Confirmation Page', 'link' => 'stationeryconfirm.php', 'icon' => 'fas fa-book'],
        ['label' => 'Report', 'link' => 'receivingbayreport.php', 'icon' => 'fas fa-book'],
        ['label' => 'Report page', 'link' => 'storereport.php', 'icon' => 'fas fa-book'],
    ],
    
    'labStore' => [
        ['label' => 'Laboratory Page', 'link' => 'labStore.php', 'icon' => 'fas fa-box'],
        // ['label' => 'Request Item Page', 'link' => 'requisitionform.php', 'icon' => 'fas fa-arrow-up'],
        ['label' => 'Item Confirmation Page', 'link' => 'labconfirm.php', 'icon' => 'fas fa-book'],
        ['label' => 'Report', 'link' => 'receivingbayreport.php', 'icon' => 'fas fa-book'],
        ['label' => 'Report page', 'link' => 'storereport.php', 'icon' => 'fas fa-book'],

    ],
    
    // 'mechanical' => [
    //     ['label' => 'Mechanical Page', 'link' => 'mechanicalStore.php', 'icon' => 'fas fa-arrow-up'],
    //     ['label' => 'Request Item Page', 'link' => 'requisitionform.php', 'icon' => 'fas fa-arrow-up'],
    // ],
    'electricalStore' => [
        ['label' => 'Electrical Page', 'link' => 'electricalStore.php', 'icon' => 'fas fa-box'],
        // ['label' => 'Request Item Page', 'link' => 'requisitionform.php', 'icon' => 'fas fa-arrow-up'],
        ['label' => 'Item Confirmation Page', 'link' => 'electricalconfirm.php', 'icon' => 'fas fa-book'],
        ['label' => 'Report', 'link' => 'receivingbayreport.php', 'icon' => 'fas fa-book'],
        ['label' => 'Report page', 'link' => 'storereport.php', 'icon' => 'fas fa-book'],
    ],
    
    'civilStore' => [
        ['label' => 'Civil Page', 'link' => 'civilStore.php', 'icon' => 'fas fa-box'],
        // ['label' => 'Request Item Page', 'link' => 'requisitionform.php', 'icon' => 'fas fa-arrow-up'],
        ['label' => 'Item Confirmation Page', 'link' => 'civilconfirm.php', 'icon' => 'fas fa-book'],
        ['label' => 'Report', 'link' => 'receivingbayreport.php', 'icon' => 'fas fa-book'],
        ['label' => 'Report page', 'link' => 'storereport.php', 'icon' => 'fas fa-book'],
    ],
    
    'controlunit' => [
        ['label' => 'Control Page', 'link' => 'fetchitems.php', 'icon' => 'fas fa-book'],
        ['label' => 'Request Item Page', 'link' => 'requisitionform.php', 'icon' => 'fas fa-edit'],
        ['label' => 'Control Unit Release Item Page', 'link' => 'controlrelease.php', 'icon' => 'fas fa-edit'],
        ['label' => 'Report', 'link' => 'reportmain.php', 'icon' => 'fas fa-book'],

    ],
  
    'receivingBay' => [
        ['label' => 'Receiving Bay', 'link' => 'receivingbay.php', 'icon' => 'fas fa-truck'],
        ['label' => 'Receiving Bay All items Page', 'link' => 'fetchitems.php', 'icon' => 'fas fa-book'],
        ['label' => 'Audit Page', 'link' => 'audit.php', 'icon' => 'fas fa-book'],
        ['label' => 'Report', 'link' => 'receivingbayreport.php', 'icon' => 'fas fa-book'],

        
    ],
    'hod' => [
        ['label' => 'medical Preview Page', 'link' => 'medStore.php', 'icon' => 'fas fa-box'],
        ['label' => 'Hardware Page', 'link' => 'hbfStore.php', 'icon' => 'fas fa-box'],
        ['label' => 'Stationery Page', 'link' => 'stationeryStore.php', 'icon' => 'fas fa-box'],
        ['label' => 'Laboratory Page', 'link' => 'labStore.php', 'icon' => 'fas fa-box'],
        ['label' => 'Electrical Page', 'link' => 'electricalStore.php', 'icon' => 'fas fa-box'],
        ['label' => 'Civil Page', 'link' => 'civilStore.php', 'icon' => 'fas fa-box'],
        ['label' => 'Control Unit Release Item Page', 'link' => 'controlrelease.php', 'icon' => 'fas fa-edit'],
        ['label' => 'All items Preview Page', 'link' => 'fetchitems.php', 'icon' => 'fas fa-edit'],
        ['label' => 'Receiving Bay', 'link' => 'receivingbay.php', 'icon' => 'fas fa-truck'],
        ['label' => 'Request Item Page', 'link' => 'requisitionform.php', 'icon' => 'fas fa-book'],
        ['label' => 'Report', 'link' => 'receivingbayreport.php', 'icon' => 'fas fa-book'],
        ['label' => 'Audit Page', 'link' => 'audit.php', 'icon' => 'fas fa-book'],
        ['label' => 'Report page', 'link' => 'storereport.php', 'icon' => 'fas fa-book'],
        ['label' => 'All Users Page', 'link' => 'user.php', 'icon' => 'fas fa-user'],
        ['label' => 'Reserved Activation Page', 'link' => 'hod.php', 'icon' => 'fas fa-user'],
        



    ],
    // Add other sections similarly...
];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventy Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="./vendor/bootstrap-5.2.3-dist/css/bootstrap.min.css" rel="stylesheet">
<script src="./vendor/bootstrap-5.2.3-dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./vendor/fontawesome-free-5.15.4-web/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        
        .sidebar {
            height: 100vh;
            background-color: #fff;
            padding: 20px;
            color: black;
            font-weight: bold;
            overflow-y:auto;
        }

        .sidebar .brand-logo {
            display: flex;
            align-items: center;
            font-weight: bold;
            justify-content: space-between;
        }

        .sidebar .brand-logo img {
            height: 50px;
           
        }

        .sidebar .nav-link {
            color: black;
            font-size: 16px;
            margin-bottom: 10px;
            padding: 15px;
            border-radius: 5px;
            /* border: 2px solid red; */
            text-transform: uppercase;
            scroll-behavior:smooth;
        }

        .sidebar .nav-link:hover {
            background-color: #1c2835;
            color: #fff;
            transition: transform 0.2s; 

        }

        .nav-link.active {
            background-color: #1b6ec2;
        }

        .sidebar .user-info {
            margin-top: 50px;
            margin-bottom: 20px;
            color: black;
        }

           

        .main-content {            
            background-color: #f4f4f9;   
            padding: 10px;     
           
        }

        .card-grid {
            display: flex;       
            
        }

        .dashboard-card {
            margin: 20px;
            display: flex;
            padding: 20px;
            box-shadow: 4px 2px 3px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s; 
            background-color: #fff;    
            flex-wrap: wrap;       
        }

        .dashboard-card:hover {
            transform: scale(1.05);
        }

        .dashboard-card i {
            font-size: 50px;
            margin-bottom: 10px;
            color: #1b6ec2;
            display: none;
        }

        .dashboard-card p {
            font-size: 18px;
            font-weight: bold;
            text-transform:uppercase;
            color: #1b6ec2;

        }
        .dashboard-card p:hover{
            color: #000;
            transition: transform 0.2s;

        }

        .header {
            background-color: #1b6ec2;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header .user-name {
            display: flex;
            align-items: center;
        }

        .header .user-name img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
            border-radius: 50%;
        }

    </style>
     
</head>
<body>
    <div class="container-fluid">
    <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <div class="brand-logo">
                    <img src="image/stock2.jfif" alt="Inventory Logo">
                    <h2 class="">OAUTHC STORE</h2>
                </div>
                <div class="user-info">
                    <h5 class="text-uppercase px-1 fw-bold "> <i class="fas fa-user-circle px-2"></i><?php  echo $_SESSION['fullname'];?></h5 class="text-uppercase px-2">
                    <!-- <small>Admin</small> -->
                </div>
                <nav class="nav flex-column">
                    <?php foreach ($common_links as $label => $data): ?>
                        <a href="<?php echo $data['link']; ?>" class="nav-link<?php echo ($label === 'Home') ? ' active' : ''; ?>">
                            <i class="<?php echo $data['icon']; ?>"></i> <?php echo $label; ?>
                        </a>
                    <?php endforeach; ?>

                       <!-- Dynamically display store-specific links -->
                       <?php if (array_key_exists($store_section, $store_links)): ?>
                        <?php foreach ($store_links[$store_section] as $store_link): ?>
                            <a href="<?php echo $store_link['link']; ?>" class="nav-link">
                                <i class="<?php echo $store_link['icon']; ?>"></i> <?php echo $store_link['label']; ?>
                            </a>
                        <?php endforeach; ?>
                        <?php endif; ?>
                       
                    <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Log Out</a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">

              <!-- Notification Button and Dropdown -->
              <div class="notification-container">
                <button onclick="toggleNotifications()" class="notification-button">
                    🔔 Notifications <span class="badge" id="notification-count">0</span>
                </button>

                <div id="notification-dropdown" class="notification-dropdown">
                    <p>No new notifications</p>  <!-- Default message when there are no notifications -->
                </div>
            </div>

                <!-- Header -->
                <div class="header">
                    <h4 class="text-uppercase py-3"> Dashboard</h4>
                    <div class="user-name">
                        <img src="image/stock1.jfif" alt="User Picture">
                        <span><?php  echo $_SESSION['fullname'];?></span>
                        <a href="logout.php" data-toggle="tooltip" data-placement="auto" title="LogOut" class="nav-link px-3"><i class="fas fa-sign-out-alt"> </i> </a>
                    
                    </div>
                </div>

                <!-- Dashboard Cards -->
                <div class="card-grid mt-4">
                    <div class="dashboard-card">
                            <!-- Render store-specific links -->
                            <?php if (array_key_exists($store_section, $store_links)): ?>
                                <?php foreach ($store_links[$store_section] as $store_link): ?>
                                    <div class="dashboard-card">
                                        <i class="<?php echo $store_link['icon']; ?>"></i>
                                        <p><a href="<?php echo $store_link['link']; ?>" class="nav-link"><?php echo $store_link['label']; ?></a></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                 
                    
                </div>
            </div>

            
        </div>
    </div>

   <!-- toogle -->
<script>
    function toggleNotifications() {
    var dropdown = document.getElementById("notification-dropdown");
    dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
    }

</script>


<!-- Fetch Notification -->
<script>
    function fetchNotifications() {
    fetch('fetch_notification.php')
        .then(response => response.json())
        .then(data => {
            console.log("Fetched Notifications:", data);  // Debugging log

            const notificationCount = document.getElementById("notification-count");
            const notificationDropdown = document.getElementById("notification-dropdown");

            // Update notification count
            if (data.notification_count > 0) {
                notificationCount.textContent = data.notification_count;
                notificationCount.style.display = "inline";
            } else {
                notificationCount.style.display = "none";
            }

            // Update notification dropdown
            if (data.notifications.length > 0) {
                let notificationsHTML = "";
                data.notifications.forEach(notification => {
                    notificationsHTML += `
                        <div style="margin-bottom: 8px;">
                            <span>${notification.message} - <small>${notification.created_at}</small></span>
                            <a href="#" onclick="markAsRead(${notification.id})" style="margin-left: 10px; color: red; text-decoration: underline; font-size: 12px;">
                                Mark as Read
                            </a>
                        </div>
               
               
                    `;
                     });
                notificationDropdown.innerHTML = notificationsHTML;

            } else {
                notificationDropdown.innerHTML = "<p>No new notifications</p>";
            }
        })
        .catch(error => {
            console.error("Error fetching notifications:", error);
        });
    }

    // Call function every 5 seconds to auto-refresh notifications
    setInterval(fetchNotifications, 5000);

    // Fetch notifications on page load
    fetchNotifications();
</script> 

<!-- Mark notification as read -->
<script>


    
    function markAsRead(notificationId) {
    fetch('mark_notification_read.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'notification_id=' + notificationId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            fetchNotifications(); // Refresh notifications after marking as read
        } else {
            console.error("Error marking as read:", data.error);
        }
    })
    .catch(error => console.error("Request failed:", error));
    }


</script>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script> -->
</body>
</html>
