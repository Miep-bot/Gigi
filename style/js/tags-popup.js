// tags-popup.js
function openTagsPopup() {
    document.getElementById('tags-popup-overlay').style.display = 'flex';
}
function closeTagsPopup() {
    document.getElementById('tags-popup-overlay').style.display = 'none';
}
// Close popup on overlay click (but not when clicking inside the popup)
document.addEventListener('DOMContentLoaded', function() {
    var overlay = document.getElementById('tags-popup-overlay');
    if (overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) closeTagsPopup();
        });
    }
});