// =============================================
// PHARMACY INVENTORY SYSTEM - JAVASCRIPT
// =============================================

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeActiveNavLink();
    initializeTooltips();
    initializePopovers();
});

// =============================================
// NAVIGATION FUNCTIONS
// =============================================

/**
 * Set active navigation link based on current page
 */
function initializeActiveNavLink() {
    const currentPage = window.location.pathname;
    const navLinks = document.querySelectorAll('.sidebar .nav-link');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (currentPage.includes(href) || currentPage.endsWith(href)) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

/**
 * Toggle sidebar visibility on mobile
 */
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
        sidebar.classList.toggle('show');
    }
}

// =============================================
// BOOTSTRAP UTILITIES
// =============================================

/**
 * Initialize Bootstrap tooltips
 */
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Initialize Bootstrap popovers
 */
function initializePopovers() {
    const popoverTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="popover"]')
    );
    popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
}

// =============================================
// ALERT & NOTIFICATION FUNCTIONS
// =============================================

/**
 * Show success message
 */
function showSuccess(message, duration = 3000) {
    showAlert('success', message, duration);
}

/**
 * Show error message
 */
function showError(message, duration = 4000) {
    showAlert('danger', message, duration);
}

/**
 * Show warning message
 */
function showWarning(message, duration = 3000) {
    showAlert('warning', message, duration);
}

/**
 * Generic alert function
 */
function showAlert(type, message, duration) {
    const alertId = 'alert-' + Date.now();
    const alertHTML = `
        <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Insert at top of main content
    const mainContent = document.querySelector('.main-content');
    if (mainContent) {
        mainContent.insertAdjacentHTML('afterbegin', alertHTML);
        
        // Auto dismiss
        if (duration > 0) {
            setTimeout(() => {
                const alert = document.getElementById(alertId);
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, duration);
        }
    }
}

// =============================================
// FORM UTILITIES
// =============================================

/**
 * Clear form fields
 */
function clearForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
    }
}

/**
 * Disable form submission button during processing
 */
function disableFormBtn(btnId) {
    const btn = document.getElementById(btnId);
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
    }
}

/**
 * Enable form submission button
 */
function enableFormBtn(btnId, originalText) {
    const btn = document.getElementById(btnId);
    if (btn) {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

// =============================================
// TABLE UTILITIES
// =============================================

/**
 * Confirm before deleting a record
 */
function confirmDelete(itemName = 'this item') {
    return confirm(`Are you sure you want to delete ${itemName}? This action cannot be undone.`);
}

/**
 * Format currency
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

/**
 * Format date
 */
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}

// =============================================
// UTILITY FUNCTIONS
// =============================================

/**
 * Get URL parameter
 */
function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    const results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

/**
 * Check if element is in viewport
 */
function isElementInViewport(el) {
    const rect = el.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

/**
 * Scroll to element
 */
function scrollToElement(selector) {
    const element = document.querySelector(selector);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// =============================================
// KEYBOARD SHORTCUTS
// =============================================

document.addEventListener('keydown', function(event) {
    // Ctrl/Cmd + K to focus search (if available)
    if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
        event.preventDefault();
        const searchInput = document.querySelector('input[type="search"]');
        if (searchInput) {
            searchInput.focus();
        }
    }
});

// =============================================
// AUTO-RELOAD CHECK
// =============================================

/**
 * Check for page updates every 5 minutes
 */
function setupAutoReloadCheck() {
    setInterval(function() {
        fetch(window.location.href, { 
            method: 'HEAD',
            cache: 'no-store'
        }).catch(function() {
            // Connection lost or server error
            console.log('Connection check failed');
        });
    }, 5 * 60 * 1000); // 5 minutes
}

// Initialize auto reload check
setupAutoReloadCheck();

