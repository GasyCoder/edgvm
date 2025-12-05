<div>
    @include('livewire.frontend.includes.section-hero')
    @include('livewire.frontend.includes.section-stats')
    @include('livewire.frontend.includes.section-about')
    @include('livewire.frontend.includes.section-messagedirection')
    @include('livewire.frontend.includes.section-programme')
    @include('livewire.frontend.includes.section-actualites')
    @include('livewire.frontend.includes.section-cta')
    @include('livewire.frontend.includes.section-partenaire')
</div>

@push('scripts')
<script>
    function slider() {
        return {
            currentSlide: 0,
            slides: @json(range(0, $slider ? $slider->slides->count() - 1 : 0)),
            interval: null,
            
            init() {
                if (this.slides.length > 1) {
                    this.startAutoplay();
                }
            },
            
            nextSlide() {
                this.currentSlide = (this.currentSlide + 1) % this.slides.length;
                this.resetAutoplay();
            },
            
            previousSlide() {
                this.currentSlide = this.currentSlide === 0 ? this.slides.length - 1 : this.currentSlide - 1;
                this.resetAutoplay();
            },
            
            goToSlide(index) {
                this.currentSlide = index;
                this.resetAutoplay();
            },
            
            startAutoplay() {
                this.interval = setInterval(() => {
                    this.nextSlide();
                }, 6000);
            },
            
            resetAutoplay() {
                clearInterval(this.interval);
                this.startAutoplay();
            }
        }
    }
</script>
@endpush