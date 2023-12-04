@if($status == 'evaluated')
    <span class="badge bg-primary">{{ ucwords($status) }}</span>
@elseif($status == 'appeal requested')
    <span class="badge bg-secondary">{{ ucwords($status) }}</span>
@elseif($status == 'appeal accepted')
    <span class="badge bg-success">{{ ucwords($status) }}</span>
@elseif($status == 'appeal rejected')
    <span class="badge bg-danger">{{ ucwords($status) }}</span>
@elseif($status == 'action taken')
    <span class="badge bg-warning">{{ ucwords($status) }}</span>
@elseif ($status == 'assigned to team lead')
<span class="badge bg-info">{{ ucwords($status) }}</span>
@endif


