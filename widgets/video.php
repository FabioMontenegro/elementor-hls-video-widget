<?php
namespace WPC\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Video extends Widget_Base
{
    private static $videoCount = 1;

    public function get_name()
    {
        return 'video-hls';
    }

    public function get_title()
    {
        return 'Video HLS';
    }

    public function get_icon()
    {
        return 'eicon-cloud-check';
    }

    public function get_group()
    {
        return ['actions'];
    }

    public function get_categories()
    {
        return ['general'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => 'Settings',
            ]
        );

        $this->add_control(
            'label_heading',
            [
                'label' => 'Titulo',
                'type' => Controls_Manager::TEXT,
                'default' => 'Titulo de ejemplo',
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'video_url',
            [
                'label' => 'URL Video m3u8',
                'type' => Controls_Manager::TEXT,
                'default' => 'https://test-streams.mux.dev/x36xhzz/x36xhzz.m3u8',
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'content',
            [
                'label' => 'Contenido',
                'type' => Controls_Manager::WYSIWYG,
                'default' => 'Agregue una descripción al video aquí.',
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __( 'Title Style', 'elementor-test-extension' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        // Title Font Control
        $this->add_control(
            'title_font',
            [
                'label' => __( 'Font', 'elementor-test-extension' ),
                'type' => Controls_Manager::FONT,
                'selectors' => [
                    '{{WRAPPER}} .video__label-heading h2' => 'font-family: {{VALUE}};',
                ],
            ]
        );
        
        // Title Color Control
        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'elementor-test-extension' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .video__label-heading h2' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_style_textarea',
            [
                'label' => __( 'Description Style', 'elementor-test-extension' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        // Textarea Font Control
        $this->add_control(
            'textarea_font',
            [
                'label' => __( 'Font', 'elementor-test-extension' ),
                'type' => Controls_Manager::FONT,
                'selectors' => [
                    '{{WRAPPER}} .video-description p' => 'font-family: {{VALUE}};',
                ],
            ]
        );
        
        // Textarea Color Control
        $this->add_control(
            'textarea_color',
            [
                'label' => __( 'Color', 'elementor-test-extension' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .video-description p' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $videoId = 'video-' . self::$videoCount;
        self::$videoCount++;
    
        ?>
        <div class="video">
            <div class="video__label-heading">
                <h2><?php echo esc_html($settings['label_heading']); ?></h2>
                <div class="video-description">
                <p><?php echo $settings['content']; ?></p>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/hls.js@1"></script>
                <video id="<?php echo $videoId; ?>" controls></video>
                <script>
                    var video = document.getElementById('<?php echo $videoId; ?>');
                    var videoSrc = "<?php echo esc_url($settings['video_url']); ?>";
    
                    if (video.canPlayType('application/vnd.apple.mpegurl')) {
                        video.src = videoSrc;
                    } else if (Hls.isSupported()) {
                        var hls = new Hls();
                        hls.loadSource(videoSrc);
                        hls.attachMedia(video);
                        hls.on(Hls.Events.ERROR, function(event, data) {
                            if (data.fatal) {
                                switch (data.type) {
                                    case Hls.ErrorTypes.NETWORK_ERROR:
                                        console.error("Error de red al cargar el video");
                                        break;
                                    default:
                                        console.error("Error al cargar el video: ", data.type);
                                        break;
                                }
                            }
                        });
                    } else {
                        console.error("Tu navegador no soporta HLS");
                    }
                </script>
            </div>
        </div>
        <?php
    }
    
    protected function _content_template() {
        ?>
        <div class="video">
            <div class="video__label-heading">{{{ settings.label_heading }}}</div>
            <div class="video-description">
                {{{ settings.content }}}
            </div>
        </div>
        <?php
    }
    
}
?>
