// Get the base path for AJAX requests
const getBasePath = () => {
    // Get the current page path and go up to the root
    const path = window.location.pathname;
    if (path.includes('/Gigi/website/')) {
        return '/Gigi/website/';
    }
    return '/';
};

const basePath = getBasePath();

// Update cart count display
function updateCartCount(count) {
    const badge = document.getElementById('cart-count-badge');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline-block' : 'none';
    }
}

// Load cart count on page load
function loadCartCount() {
    fetch(basePath + 'get_cart_count.php')
        .then(response => response.json())
        .then(data => updateCartCount(data.cartCount))
        .catch(err => console.error('Error loading cart count:', err));
}

// Handle add to cart with popup
function addToCartWithPopup(event, productId) {
    event.preventDefault();
    
    const formData = new FormData();
    formData.append('product_id', productId);
    
    fetch(basePath + 'add_to_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount(data.cartCount);
            showCartPopup();
        } else if (data.requiresLogin) {
            window.location.href = basePath + 'login.php';
        } else {
            console.error('Error:', data.message);
            alert(data.message || 'Error adding item to cart');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Error adding item to cart: ' + err.message);
    });
}

// Show popup with options
function showCartPopup() {
    const popup = document.getElementById('cart-popup');
    if (!popup) return;
    
    // Hide popup first to reset animation
    popup.style.display = 'none';
    
    // Trigger reflow to restart animation
    void popup.offsetWidth;
    
    // Show the popup again with fresh animation
    popup.style.display = 'block';
}

// Handle popup hover
function handlePopupHover() {
    isPopupHovered = true;
}

// Handle popup mouse leave
function handlePopupLeave() {
    isPopupHovered = false;
    const popup = document.getElementById('cart-popup');
    if (popup && popup.style.display === 'block') {
        popup.style.display = 'none';
    }
}

// Hide popup only if not hovered
function hidePopupIfNotHovered() {
    const popup = document.getElementById('cart-popup');
    if (!popup) return;
    
    if (isPopupHovered) {
        // User is hovering, wait for them to leave
        popup.addEventListener('mouseleave', function hideOnLeave() {
            hidePopupWithAnimation();
            popup.removeEventListener('mouseleave', hideOnLeave);
        });
    } else {
        // User is not hovering, hide with animation
        hidePopupWithAnimation();
    }
}

// Helper function to hide popup with slide-out animation
function hidePopupWithAnimation() {
    const popup = document.getElementById('cart-popup');
    if (!popup) return;
    
    popup.classList.add('cart-popup-exit');
    
    setTimeout(() => {
        popup.style.display = 'none';
        popup.classList.remove('cart-popup-exit');
    }, 300);
}

// Close popup with animation
function closeCartPopup() {
    const popup = document.getElementById('cart-popup');
    if (popup) {
        // Add exit animation
        popup.classList.add('cart-popup-exit');
        
        // Wait for animation to complete before hiding
        setTimeout(() => {
            popup.style.display = 'none';
            popup.classList.remove('cart-popup-exit');
        }, 300);
    }
    // Clear timeout when manually closing
    if (cartPopupTimeout) {
        clearTimeout(cartPopupTimeout);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCartCount();
});
