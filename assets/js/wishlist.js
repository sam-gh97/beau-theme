class WishlistManager {
    constructor() {
        this.wishlist = [];
        this.init();
    }

    async init() {
        if (!window.isLoggedIn) {
            console.warn('Wishlist disabled for logged-out users');
            return;
        }

        await this.loadWishlistFromServer();

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.bindEvents());
        } else {
            this.bindEvents();
        }
    }

    async loadWishlistFromServer() {
        try {
            const resp = await fetch('/wp-admin/admin-ajax.php?action=get_wishlist');
            const json = await resp.json();
            this.wishlist = Array.isArray(json.data) ? json.data : [];
        } catch (e) {
            console.error('Could not load wishlist from server', e);
            this.wishlist = [];
        }
    }

    async saveWishlistToServer() {
        const formData = new FormData();
        formData.append('action', 'save_wishlist');
        formData.append('wishlist', JSON.stringify(this.wishlist));

        try {
            await fetch('/wp-admin/admin-ajax.php', {
                method: 'POST',
                body: formData,
            });
        } catch (e) {
            console.error('Could not save wishlist', e);
        }
    }

    bindEvents() {
        document.querySelectorAll('.wishlist-btn').forEach(btn => {
            this.updateButtonState(btn);
            btn.addEventListener('click', (e) => this.handleWishlistClick(e));
        });

        this.updateHeaderCount();
    }

    isInWishlist(productId) {
        return this.wishlist.some(item => item.id === productId);
    }

    async addToWishlist(productData) {
        if (!this.isInWishlist(productData.id)) {
            this.wishlist.push(productData);
            await this.saveWishlistToServer();
            return true;
        }
        return false;
    }

    async removeFromWishlist(productId) {
        const index = this.wishlist.findIndex(item => item.id === productId);
        if (index > -1) {
            this.wishlist.splice(index, 1);
            await this.saveWishlistToServer();
            return true;
        }
        return false;
    }

    async handleWishlistClick(e) {
        e.preventDefault();
        e.stopPropagation();

        if (!window.isLoggedIn) {
            // Trigger the login modal
            const modal = document.getElementById('wishlist-login-modal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
            return;
        }

        const btn = e.currentTarget;
        const productId = btn.dataset.productId;
        const productTitle = btn.dataset.productTitle;
        const productUrl = btn.dataset.productUrl;
        const productImage = btn.dataset.productImage;

        const data = {
            id: productId,
            title: productTitle,
            url: productUrl,
            image: productImage
        };

        const exists = this.isInWishlist(productId);

        if (exists) {
            await this.removeFromWishlist(productId);
            this.updateButtonState(btn);
            this.showNotification('Removed from wishlist');
        } else {
            await this.addToWishlist(data);
            this.updateButtonState(btn);
            this.pulseHeaderIcon();
            this.showNotification('Added to wishlist!');
        }

        this.updateHeaderCount();

        if (document.body.classList.contains('page-template-template-wishlist')) {
            this.renderWishlistPage();
        }
    }

    updateButtonState(btn) {
        const productId = btn.dataset.productId;
        const exists = this.isInWishlist(productId);
        const path = btn.querySelector('path');
        const text = btn.querySelector('p, span');

        if (exists) {
            if (path) path.setAttribute('fill', 'black');
            if (text) text.textContent = 'Remove from wishlist';
            btn.classList.add('in-wishlist');
        } else {
            if (path) path.setAttribute('fill', 'none');
            if (text) text.textContent = 'Add to wishlist';
            btn.classList.remove('in-wishlist');
        }
    }

    updateHeaderCount() {}

    pulseHeaderIcon() {
        const icon = document.querySelector('.wishlist-icon');
        if (icon) {
            icon.classList.add('pulse-animation');
            setTimeout(() => icon.classList.remove('pulse-animation'), 600);
        }
    }

    showNotification(message) {
        if (window.innerWidth < 1024) return;

        const existing = document.querySelector('.wishlist-notification');
        if (existing) existing.remove();

        const div = document.createElement('div');
        div.className = 'wishlist-notification fixed bottom-8 right-8 bg-black text-white px-6 py-3 rounded-sm shadow-lg z-50 animate-slide-up';
        div.textContent = message;

        document.body.appendChild(div);

        setTimeout(() => {
            div.classList.add('fade-out');
            setTimeout(() => div.remove(), 300);
        }, 2000);
    }

    renderWishlistPage() {
        const container = document.getElementById('wishlist-products');
        const emptyMessage = document.getElementById('wishlist-empty');

        if (!container) return;

        if (this.wishlist.length === 0) {
            container.innerHTML = '';
            if (emptyMessage) emptyMessage.style.display = 'block';
            return;
        }

        if (emptyMessage) emptyMessage.style.display = 'none';

        container.innerHTML = this.wishlist.map(product => `
            <article class="product-card relative flex flex-col h-full p-4">
                <a href="${product.url}" class="block mb-4">
                    <div class="aspect-square overflow-hidden">
                        <img src="${product.image}" alt="${product.title}" class="w-full h-full object-cover object-center">
                    </div>
                </a>

                <h3 class="display-3 !mb-4">
                    <a href="${product.url}" class="hover:underline">${product.title}</a>
                </h3>
                <a href="${product.url}" class="ui-btn text-center mt-auto mb-4">Find out more</a>

                <div class="flex justify-between items-center">
                    <label class="flex items-center gap-2 body-2 cursor-pointer">
                        <input type="checkbox" name="compare[]" value="${product.id}" class="compare-checkbox w-[20px] h-[20px] rounded-none cursor-pointer accent-black">
                        <span>Compare</span>
                    </label>
                    <button 
                        class="wishlist-btn flex items-center gap-2 body-2 cursor-pointer hover:opacity-70 transition-opacity in-wishlist"
                        data-product-id="${product.id}"
                        data-product-title="${product.title}"
                        data-product-url="${product.url}"
                        data-product-image="${product.image}"
                        aria-label="Add to wishlist"
                    >
                        <svg width="22" height="19" viewBox="0 0 22 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.9209 2.09907C16.7888 -0.0330236 13.3314 -0.0330236 11.1993 2.09907C10.9364 2.362 10.7082 2.64561 10.51 2.94323C10.3118 2.64561 10.0836 2.362 9.82066 2.09974C7.68856 -0.0323563 4.23117 -0.0323563 2.09907 2.09974C-0.0330236 4.23183 -0.0330236 7.68923 2.09907 9.82132L10.51 18.2316L18.9209 9.82066C21.053 7.68856 21.053 4.23183 18.9209 2.09907Z" stroke="black" fill="black" stroke-miterlimit="10" stroke-linecap="square"/>
                        </svg>
                    </button>
                </div>

                <span class="border-left"></span>
                <span class="border-right"></span>
                <span class="border-bottom-left"></span>
                <span class="border-bottom-right"></span>
            </article>
        `).join('');

        container.querySelectorAll('.wishlist-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleWishlistClick(e));
        });
    }
}

const wishlistManager = new WishlistManager();
export default wishlistManager;
