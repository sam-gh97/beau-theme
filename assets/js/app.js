import '../css/style.css';
import '../css/layout.css';
import '../css/editor.css';
import '../css/ui.css';
import '../css/form.css';
import '../css/body-content.css';
import '../css/woocommerce.css';
import '../css/typography.css';
import '../css/block-settings.css';

// Swiper CSS
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/scrollbar';

// gsap
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
gsap.registerPlugin(ScrollTrigger);

import '@fancyapps/ui/dist/fancybox/fancybox.css';

import { initSliders } from './sliders.js';

import wishlistManager from './wishlist.js';
window.wishlistManager = wishlistManager;

import Alpine from 'alpinejs'

window.Alpine = Alpine
Alpine.start()

// Accordion
document.addEventListener('DOMContentLoaded', function() {
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    
    accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const accordionItem = this.closest('.accordion-item');
            const accordionContent = accordionItem.querySelector('.accordion-content');
            const isOpen = accordionItem.classList.contains('is-open');
            
            if (isOpen) {
                accordionItem.classList.remove('is-open');
                accordionContent.style.display = 'none';
                this.setAttribute('aria-expanded', 'false');
            } else {
                accordionItem.classList.add('is-open');
                accordionContent.style.display = 'block';
                this.setAttribute('aria-expanded', 'true');
            }
        });
    });

    // Mobile Menu
    const hamburger = document.querySelector('.hamburger-menu');
    const mobileMenuContainer = document.getElementById('mobile-menu-container');
    const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
    
    function openMobileMenu() {
        mobileMenuContainer.classList.remove('translate-x-full');
        mobileMenuOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        hamburger.classList.add('active');
    }
    
    function closeMobileMenu() {
        mobileMenuContainer.classList.add('translate-x-full');
        mobileMenuOverlay.classList.add('hidden');
        document.body.style.overflow = '';
        hamburger.classList.remove('active');
    }
    
    if (hamburger) {
        hamburger.addEventListener('click', function() {
            const isOpen = !mobileMenuContainer.classList.contains('translate-x-full');
            if (isOpen) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        });
    }
    
    if (mobileMenuOverlay) {
        mobileMenuOverlay.addEventListener('click', closeMobileMenu);
    }
    
    // Mobile Submenu Accordion
    const submenuToggles = document.querySelectorAll('.mobile-submenu-toggle');
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const menuItem = this.closest('.mobile-menu-item');
            const submenu = menuItem.querySelector('.mobile-submenu');
            const chevron = this.querySelector('img');
            
            if (submenu) {
                const isOpen = !submenu.classList.contains('hidden');
                
                if (isOpen) {
                    submenu.classList.add('hidden');
                    chevron.style.transform = 'rotate(0deg)';
                } else {
                    submenu.classList.remove('hidden');
                    chevron.style.transform = 'rotate(180deg)';
                }
            }
        });
    });

    initSliders();
});

document.querySelectorAll('[class*="anim-"]').forEach(el => {
    const cls = [...el.classList].find(c => c.startsWith('anim-'));
    const type = cls.replace('anim-', '');
  
    const presets = {
      'fade-up': { 
        from: { y: 30, opacity: 0 },
        to: { y: 0, opacity: 1 }
      },
      'zoom': { 
        from: { scale: 0.95, opacity: 0 },
        to: { scale: 1, opacity: 1 }
      }
    };
  
    const animation = presets[type];
  
    gsap.fromTo(
      el,
      animation.from,
      {
        ...animation.to,
        duration: 1.5,
        ease: 'power2.out',
        scrollTrigger: {
          trigger: el,
          start: 'top 80%'
        }
      }
    );
  });
  