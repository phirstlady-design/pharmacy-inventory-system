// Global variables
let currentPage = 1;
const itemsPerPage = 10;

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

// Initialize Application
function initializeApp() {
    // Mobile sidebar toggle
    initializeMobileSidebar();
    
    // Form validations
    initializeFormValidations();
    
    // Search functionality
    initializeSearch();
    
    // Auto-refresh for dashboard
    if (window.location.pathname.includes('index.php')) {
        setInterval(refreshDashboardStats, 30000); // Refresh every 30 seconds
    }
    
    // Initialize tooltips
    initializeTooltips();
}

// Mobile Sidebar
function initializeMobileSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    
    // Create mobile menu button
    if (window.innerWidth <= 768) {
        const menuButton = document.createElement('button');
        menuButton.className = 'mobile-menu-btn';
        menuButton.innerHTML = '<i class="fas fa-bars"></i>';
        menuButton.style.cssText = `
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: #667eea;
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 8px;
            cursor: pointer;
        `;
        
        document.body.appendChild(menuButton);
        
        menuButton.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
        
        // Close sidebar when clicking outside
        document.addEventListener('click', function(e) {
            if (!sidebar.contains(e.target) && !menuButton.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    }
}

// Form Validations
function initializeFormValidations() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
        
        // Real-time validation
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
        });
    });
}

// Validate Form
function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });
    
    // Custom validations
    const priceField = form.querySelector('input[name="price"]');
    if (priceField && parseFloat(priceField.value) < 0) {
        showFieldError(priceField, 'Price cannot be negative');
        isValid = false;
    }
    
    const quantityField = form.querySelector('input[name="quantity"]');
    if (quantityField && parseInt(quantityField.value) < 0) {
        showFieldError(quantityField, 'Quantity cannot be negative');
        isValid = false;
    }
    
    const expiryField = form.querySelector('input[name="expiry_date"]');
    if (expiryField && new Date(expiryField.value) < new Date()) {
        showFieldError(expiryField, 'Expiry date cannot be in the past');
        isValid = false;
    }
    
    return isValid;
}

// Validate Individual Field
function validateField(field) {
    clearFieldError(field);
    
    if (field.hasAttribute('required') && !field.value.trim()) {
        showFieldError(field, 'This field is required');
        return false;
    }
    
    if (field.type === 'email' && field.value && !isValidEmail(field.value)) {
        showFieldError(field, 'Please enter a valid email address');
        return false;
    }
    
    if (field.type === 'number' && field.value && isNaN(field.value)) {
        showFieldError(field, 'Please enter a valid number');
        return false;
    }
    
    return true;
}

// Show Field Error
function showFieldError(field, message) {
    field.style.borderColor = '#e53e3e';
    
    let errorElement = field.parentNode.querySelector('.field-error');
    if (!errorElement) {
        errorElement = document.createElement('span');
        errorElement.className = 'field-error';
        errorElement.style.cssText = 'color: #e53e3e; font-size: 0.75rem; margin-top: 0.25rem;';
        field.parentNode.appendChild(errorElement);
    }
    
    errorElement.textContent = message;
}

// Clear Field Error
function clearFieldError(field) {
    field.style.borderColor = '#e2e8f0';
    
    const errorElement = field.parentNode.querySelector('.field-error');
    if (errorElement) {
        errorElement.remove();
    }
}

// Email Validation
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Search Functionality
function initializeSearch() {
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch(this.value);
            }, 300);
        });
    }
}

// Perform Search
function performSearch(query) {
    // This would typically make an AJAX call to search
    console.log('Searching for:', query);
    
    // For now, we'll just filter the visible table rows
    const tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const matches = text.includes(query.toLowerCase());
        row.style.display = matches ? '' : 'none';
    });
}

// Delete Medicine
function deleteMedicine(id) {
    if (confirm('Are you sure you want to delete this medicine? This action cannot be undone.')) {
        // Show loading state
        showLoading();
        
        // Make AJAX request to delete
        fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'delete_medicine',
                id: id
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.success) {
                showAlert('Medicine deleted successfully', 'success');
                // Remove the row from table
                const row = document.querySelector(`tr[data-id="${id}"]`);
                if (row) {
                    row.remove();
                }
                // Refresh page after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showAlert('Error deleting medicine: ' + data.message, 'error');
            }
        })
        .catch(error => {
            hideLoading();
            showAlert('Error deleting medicine', 'error');
            console.error('Error:', error);
        });
    }
}

// Show Alert
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        ${message}
    `;
    
    // Insert at the top of main content
    const mainContent = document.querySelector('.main-content');
    const header = mainContent.querySelector('.header');
    mainContent.insertBefore(alertDiv, header.nextSibling);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
    
    // Add click to dismiss
    alertDiv.addEventListener('click', () => {
        alertDiv.remove();
    });
}

// Show Loading
function showLoading() {
    const loadingDiv = document.createElement('div');
    loadingDiv.id = 'loading-overlay';
    loadingDiv.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        color: white;
        font-size: 1.2rem;
    `;
    loadingDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    
    document.body.appendChild(loadingDiv);
}

// Hide Loading
function hideLoading() {
    const loadingDiv = document.getElementById('loading-overlay');
    if (loadingDiv) {
        loadingDiv.remove();
    }
}

// Refresh Dashboard Stats
function refreshDashboardStats() {
    fetch('api.php?action=get_stats')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateDashboardStats(data.stats);
            }
        })
        .catch(error => {
            console.error('Error refreshing stats:', error);
        });
}

// Update Dashboard Stats
function updateDashboardStats(stats) {
    const statCards = document.querySelectorAll('.stat-card');
    
    if (statCards.length >= 4) {
        statCards[0].querySelector('h3').textContent = stats.total_medicines;
        statCards[1].querySelector('h3').textContent = stats.low_stock;
        statCards[2].querySelector('h3').textContent = stats.expired;
        statCards[3].querySelector('h3').textContent = stats.categories;
    }
}

// Initialize Tooltips
function initializeTooltips() {
    const elementsWithTooltips = document.querySelectorAll('[title]');
    
    elementsWithTooltips.forEach(element => {
        element.addEventListener('mouseenter', function(e) {
            showTooltip(e.target, e.target.getAttribute('title'));
        });
        
        element.addEventListener('mouseleave', function() {
            hideTooltip();
        });
    });
}

// Show Tooltip
function showTooltip(element, text) {
    const tooltip = document.createElement('div');
    tooltip.id = 'tooltip';
    tooltip.textContent = text;
    tooltip.style.cssText = `
        position: absolute;
        background: #2d3748;
        color: white;
        padding: 0.5rem 0.75rem;
        border-radius: 4px;
        font-size: 0.75rem;
        z-index: 1000;
        pointer-events: none;
        white-space: nowrap;
    `;
    
    document.body.appendChild(tooltip);
    
    const rect = element.getBoundingClientRect();
    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';
}

// Hide Tooltip
function hideTooltip() {
    const tooltip = document.getElementById('tooltip');
    if (tooltip) {
        tooltip.remove();
    }
}

// Export Functions
function exportToCSV() {
    const table = document.querySelector('.inventory-table');
    if (!table) return;
    
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    rows.forEach(row => {
        const cols = row.querySelectorAll('th, td');
        const rowData = [];
        
        cols.forEach(col => {
            rowData.push('"' + col.textContent.trim().replace(/"/g, '""') + '"');
        });
        
        csv.push(rowData.join(','));
    });
    
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    
    const a = document.createElement('a');
    a.href = url;
    a.download = 'pharmacy_inventory.csv';
    a.click();
    
    window.URL.revokeObjectURL(url);
}

// Print Function
function printInventory() {
    window.print();
}

// Utility Functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Auto-save form data
function initializeAutoSave() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            // Load saved data
            const savedValue = localStorage.getItem(`form_${input.name}`);
            if (savedValue && input.type !== 'password') {
                input.value = savedValue;
            }
            
            // Save on change
            input.addEventListener('input', debounce(() => {
                if (input.type !== 'password') {
                    localStorage.setItem(`form_${input.name}`, input.value);
                }
            }, 500));
        });
        
        // Clear saved data on successful submit
        form.addEventListener('submit', () => {
            inputs.forEach(input => {
                localStorage.removeItem(`form_${input.name}`);
            });
        });
    });
}

// Initialize auto-save
document.addEventListener('DOMContentLoaded', initializeAutoSave);