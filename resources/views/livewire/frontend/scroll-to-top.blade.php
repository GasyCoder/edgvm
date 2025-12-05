<div>
<!-- Scroll to Top Button - DESIGN MODERNE -->
<div x-data="scrollToTop()" 
     x-init="init()"
     x-show="showButton"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-10"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-10"
     class="fixed bottom-6 right-6 md:bottom-8 md:right-8 z-50">
    
    <button @click="scrollToTop()"
            class="group relative w-14 h-14 bg-gradient-to-br from-ed-primary to-ed-secondary rounded-full shadow-2xl hover:shadow-glow transition-all duration-300 hover:scale-110 flex items-center justify-center overflow-hidden">
        
        <!-- Effet de vague au hover -->
        <div class="absolute inset-0 bg-white/20 rounded-full scale-0 group-hover:scale-100 transition-transform duration-500"></div>
        
        <!-- Cercle de progression -->
        <svg class="absolute inset-0 w-full h-full -rotate-90" viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="45" 
                    fill="none" 
                    stroke="rgba(255,255,255,0.2)" 
                    stroke-width="4"/>
            <circle cx="50" cy="50" r="45" 
                    fill="none" 
                    stroke="rgb(248, 188, 36)" 
                    stroke-width="4"
                    stroke-linecap="round"
                    :stroke-dasharray="circumference"
                    :stroke-dashoffset="dashOffset"
                    class="transition-all duration-150"
                    style="filter: drop-shadow(0 0 4px rgba(248, 188, 36, 0.5))"/>
        </svg>
        
        <!-- Icône flèche -->
        <svg class="w-6 h-6 text-white relative z-10 group-hover:-translate-y-1 transition-transform duration-300" 
             fill="none" 
             stroke="currentColor" 
             viewBox="0 0 24 24">
            <path stroke-linecap="round" 
                  stroke-linejoin="round" 
                  stroke-width="2.5" 
                  d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
        
        <!-- Effet de pulse -->
        <span class="absolute inset-0 rounded-full bg-ed-yellow/30 animate-ping opacity-75"></span>
    </button>
    
    <!-- Tooltip -->
    <div class="absolute bottom-full right-0 mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
        <div class="bg-gray-900 text-white text-xs font-medium px-3 py-2 rounded-lg shadow-lg whitespace-nowrap">
            Retour en haut
            <div class="absolute top-full right-6 -mt-1">
                <div class="border-4 border-transparent border-t-gray-900"></div>
            </div>
        </div>
    </div>
</div>
</div>
@push('scripts')
<script>
function scrollToTop() {
    return {
        showButton: false,
        scrollProgress: 0,
        circumference: 283, // 2 * π * 45
        
        init() {
            // Écouter le scroll
            window.addEventListener('scroll', () => {
                this.handleScroll();
            });
        },
        
        handleScroll() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            
            // Afficher le bouton après 300px de scroll
            this.showButton = scrollTop > 300;
            
            // Calculer la progression
            this.scrollProgress = (scrollTop / scrollHeight) * 100;
        },
        
        get dashOffset() {
            // Inverser la progression pour que le cercle se remplisse en scrollant vers le bas
            return this.circumference - (this.scrollProgress / 100) * this.circumference;
        },
        
        scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    }
}
</script>
@endpush

