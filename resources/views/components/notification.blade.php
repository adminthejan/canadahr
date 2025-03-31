<div 
    x-data="{ show: false, message: '' }" 
    x-show="show" 
    x-init="window.addEventListener('notify', event => { 
        message = event.detail.message; 
        show = true; 
        setTimeout(() => show = false, 3000);
    })" 
    class="fixed top-0 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-blue-500 to-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50"
    style="display: none;"
>
    <p x-text="message"></p>
</div>
