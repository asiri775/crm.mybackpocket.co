<div class="mail-layout-with-sidebar-left">
    <ul>
        <li>
            <a class="{{request('route') === 'inbox' ? 'active': ''}}" href="{{ route('admin.mail.index', ['route' => 'inbox']) }}">
                <span>Inbox</span>
            </a>
        </li>
        <li>
            <a class="{{request('route') === 'draft' ? 'active': ''}}" href="{{ route('admin.mail.index', ['route' => 'draft']) }}">
                <span>Draft</span>
            </a>
        </li>
        <li>
            <a class="{{request('route') === 'outbox' ? 'active': ''}}" href="{{ route('admin.mail.index', ['route' => 'outbox']) }}">
                <span>Outbox</span>
            </a>
        </li>
        <li>
            <a class="{{request('route') === 'sent' ? 'active': ''}}" href="{{ route('admin.mail.index', ['route' => 'sent']) }}">
                <span>Sent</span>
            </a>
        </li>
        <li>
            <a class="{{request('route') === 'trash' ? 'active': ''}}" href="{{ route('admin.mail.index', ['route' => 'trash']) }}">
                <span>Trash</span>
            </a>
        </li>
    </ul>
</div>
