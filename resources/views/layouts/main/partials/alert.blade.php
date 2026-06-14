@if($errors->any())
    <show-alert
        message="{{ implode('', $errors->all()) }}"
        type="error"
        :is_toast="true"
    ></show-alert>
@endif

@if(session()->exists('danger') || session()->exists('error'))
    <show-alert
        message="{{ session()->get('danger') ?? session()->get('error') }}"
        type="error"
        :is_toast="true"
    ></show-alert>
@endif

@if(session()->exists('success'))
    <show-alert
        message="{{ session()->get('success') }}"
        type="success"
        :is_toast="true"
    ></show-alert>
@endif

@if(session()->exists('warning'))
    <show-alert
        message="{{ session()->get('warning') }}"
        type="warning"
        :is_toast="true"
    ></show-alert>
@endif
