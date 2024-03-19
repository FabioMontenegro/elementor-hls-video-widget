<?php
namespace WPC\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;

// Prevent direct access to this file
if (!defined('ABSPATH')) exit;

// Video widget class definition
class Video extends Widget_Base
{
    private static $videoCount = 1; // Static variable to track the number of videos

    // Method to retrieve the widget name
    public function get_name()
    {
        return 'video-hls';
    }

    // Method to retrieve the widget title
    public function get_title()
    {
        return 'Video HLS';
    }

    // Method to retrieve the widget icon
    public function get_icon()
    {
        return 'eicon-cloud-check';
    }

    // Method to specify the widget group
    public function get_group()
    {
        return ['actions'];
    }

    // Method to specify the widget categories
    public function get_categories()
    {
        return ['general'];
    }

    // Method to register controls (inputs) for the widget
    protected function register_controls()
    {
        // Section for widget settings
        $this->start_controls_section(
            'section_content',
            [
                'label' => 'Settings',
            ]
        );

        // Control for video title
        $this->add_control(
            'label_heading',
            [
                'label' => 'Title',
                'type' => Controls_Manager::TEXT,
                'default' => 'Example Title',
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        // Control for video URL
        $this->add_control(
            'video_url',
            [
                'label' => 'Video URL (m3u8)',
                'type' => Controls_Manager::TEXT,
                'default' => 'https://test-streams.mux.dev/x36xhzz/x36xhzz.m3u8',
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        // Control for video content
        $this->add_control(
            'content',
            [
                'label' => 'Content',
                'type' => Controls_Manager::WYSIWYG,
                'default' => 'Add video description here.',
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->end_controls_section();
        
        // Section for title style controls
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __( 'Title Style', 'elementor-test-extension' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        // Control for title font
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
        
        // Control for title color
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
        
        // Section for description style controls
        $this->start_controls_section(
            'section_style_textarea',
            [
                'label' => __( 'Description Style', 'elementor-test-extension' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        // Control for description font
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
        
        // Control for description color
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

    // Method to render the widget output
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
                                        console.error("Network error while loading the video");
                                        break;
                                    default:
                                        console.error("Error loading the video: ", data.type);
                                        break;
                                }
                            }
                        });
                    } else {
                        console.error("Your browser does not support HLS");
                    }
                </script>
            </div>
        </div>
        <?php
    }
    
    // Method to define the structure of the widget in the editor
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
