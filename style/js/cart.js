 
const getBasePath = () => {
    
    const path = window.location.pathname;
    if (path.includes('/Gigi/website/')) {
        return '/Gigi/website/';
    }
    return '/';
};

const basePath = getBasePath();

 
function updateCartCount(count) {
    const badge = document.getElementById('cart-count-badge');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline-block' : 'none';
    }
}

 
function loadCartCount() {
    fetch(basePath + 'get_cart_count.php')
        .then(response => response.json())
        .then(data => updateCartCount(data.cartCount))
        .catch(err => console.error('Error loading cart count:', err));
}

 
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

 
function showCartPopup() {
    const popup = document.getElementById('cart-popup');
    if (!popup) return;
    
    
    popup.style.display = 'none';
    
    
    void popup.offsetWidth;
    
    
    popup.style.display = 'block';
}

 
function handlePopupHover() {
    isPopupHovered = true;
}

 
function handlePopupLeave() {
    isPopupHovered = false;
    const popup = document.getElementById('cart-popup');
    if (popup && popup.style.display === 'block') {
        popup.style.display = 'none';
    }
}

 
function hidePopupIfNotHovered() {
    const popup = document.getElementById('cart-popup');
    if (!popup) return;
    
    if (isPopupHovered) {
        
        popup.addEventListener('mouseleave', function hideOnLeave() {
            hidePopupWithAnimation();
            popup.removeEventListener('mouseleave', hideOnLeave);
        });
    } else {
        
        hidePopupWithAnimation();
    }
}

 
function hidePopupWithAnimation() {
    const popup = document.getElementById('cart-popup');
    if (!popup) return;
    
    popup.classList.add('cart-popup-exit');
    
    setTimeout(() => {
        popup.style.display = 'none';
        popup.classList.remove('cart-popup-exit');
    }, 300);
}

 
function closeCartPopup() {
    const popup = document.getElementById('cart-popup');
    if (popup) {
        
        popup.classList.add('cart-popup-exit');
        
        
        setTimeout(() => {
            popup.style.display = 'none';
            popup.classList.remove('cart-popup-exit');
        }, 300);
    }
    
    if (cartPopupTimeout) {
        clearTimeout(cartPopupTimeout);
    }
}

 
document.addEventListener('DOMContentLoaded', function() {
    loadCartCount();
});
