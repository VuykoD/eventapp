

    <?php
        $flashes = [
            'flash_success' => 'success',
            'flash_warning' => 'warning',
            'flash_info' => 'info',
            'flash_danger' => 'error',
            'flash_error' => 'error',
            'flash_message' => 'info',
        ];

        foreach ($flashes as $flash_type => $toastr_type) {
            $flash_messages = (array)session()->pull($flash_type);
            if (empty($flash_messages)) {
                continue;
            }
            foreach ($flash_messages as $toastr_message) {
                ?>
                    @include('partials.flash-toastr')
                <?php
            }
        }
        
    ?>

   