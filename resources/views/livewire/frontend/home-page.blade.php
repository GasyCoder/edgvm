<div>
    @include('livewire.frontend.includes.section-hero')
    @include('livewire.frontend.includes.section-stats')
    @include('livewire.frontend.includes.section-about')

    @include('livewire.frontend.includes.section-messagedirection', [
        'messageDirection' => $messageDirection,
        'stats'            => $stats,
    ])

    @include('livewire.frontend.includes.section-programme')

    {{-- 🔹 Nouvelle section combinée Actualités + Agenda --}}
    @include('livewire.frontend.includes.section-actualites-agenda', [
        'actualites'       => $actualites,
        'evenementsFuturs' => $evenementsFuturs,
    ])

    @include('livewire.frontend.includes.section-partenaire')
    @include('livewire.frontend.includes.section-cta')
</div>

@push('scripts')
<script>
    function slider() {
        return {
            currentSlide: 0,
            direction: 1, // 1 = next (droite->gauche), -1 = prev (gauche->droite)
            slides: @json(range(0, $slider ? max($slider->slides->count() - 1, 0) : 0)),
            interval: null,

            init() {
                if (this.slides.length > 1) this.startAutoplay();
            },

            nextSlide() {
                if (!this.slides.length) return;
                this.direction = 1;
                this.currentSlide = (this.currentSlide + 1) % this.slides.length;
                this.resetAutoplay();
            },

            previousSlide() {
                if (!this.slides.length) return;
                this.direction = -1;
                this.currentSlide = (this.currentSlide === 0) ? (this.slides.length - 1) : (this.currentSlide - 1);
                this.resetAutoplay();
            },

            goToSlide(index) {
                if (!this.slides.length) return;
                if (index === this.currentSlide) return;
                this.direction = (index > this.currentSlide) ? 1 : -1;
                this.currentSlide = index;
                this.resetAutoplay();
            },

            startAutoplay() {
                this.interval = setInterval(() => this.nextSlide(), 7000);
            },

            resetAutoplay() {
                if (!this.interval) return;
                clearInterval(this.interval);
                this.startAutoplay();
            },

            enterStart() {
                return this.direction === 1 ? 'opacity-0 translate-x-10' : 'opacity-0 -translate-x-10';
            },

            leaveEnd() {
                return this.direction === 1 ? 'opacity-0 -translate-x-10' : 'opacity-0 translate-x-10';
            }
        }
    }
</script>
@endpush


