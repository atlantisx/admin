@if( isset($_status) )
<div class="alert alert-{{ $_status['type'] }}">
    <button data-dismiss="alert" class="close" type="button">×</button>
    {{ $_status['message'] }}
</div>
@endif