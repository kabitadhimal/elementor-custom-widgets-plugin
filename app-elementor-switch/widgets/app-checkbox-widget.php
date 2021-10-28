<?php
class App_Checkbox_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'App-elementor-switch';
    }

    public function get_title() {
        return __( 'Switch Box', 'App-elementor-switch' );
    }

    public function get_icon() {
        return 'eicon-checkbox';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'App-elementor-switch' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'block_1',
            [
                'label' => __( 'Primary Block Id', 'App-elementor-switch' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'title' => __( 'Id of the default section', 'App-elementor-switch' ),
            ]
        );

        $this->add_control(
            'block_2',
            [
                'label' => __( 'Secondary Block Id', 'App-elementor-switch' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'title' => __( 'Id of the section to show when switch is turned on', 'App-elementor-switch' ),
            ]
        );

        $this->add_control(
            'state',
            [
                'label' => __( 'Default State', 'App-elementor-switch' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',

            ]
        );



        $this->end_controls_section();
    }


    protected function render() {
        // generate the final HTML on the frontend using PHP
        $settings = $this->get_settings_for_display();

        /*
        if(!isset($settings['state'])){
            $settings['state'] = 'true';
        }
        */

       // var_dump($settings['column_one']);
?>  <label class="def-switch">
                    <input type="checkbox" <?=($settings['state'] === 'yes') ? 'checked' : '' ?> />
                    <span></span>
                </label>
                <script>
                    window.addEventListener('DOMContentLoaded', function () {
                        var blockFirst = document.getElementById("<?=$settings['block_1']?>");
                        var blockSecond = document.getElementById("<?=$settings['block_2']?>");

                        if(!blockFirst || !blockSecond) return;

                        var state = <?=($settings['state'] === 'yes') ? 'true' : 'false' ?>;

                        function toggleBlocks(state) {
                            if(state){
                                blockFirst.classList.add('def-d-none');
                                blockSecond.classList.remove('def-d-none');
                            }else{
                                blockFirst.classList.remove('def-d-none');
                                blockSecond.classList.add('def-d-none');
                            }
                        }
                        document.querySelector('.def-switch input').addEventListener('change', function (event) {
                            toggleBlocks(event.currentTarget.checked);
                        })
                        toggleBlocks(state);
                    });

                </script>
            <?php
    }
}
