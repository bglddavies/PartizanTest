<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="treeview @if(in_array($menu, ['adm_org-members', 'adm_org-details'])) {{'active'}} @endif">
                <a href="#">
                    <i class="fa fa-building"></i> <span>My Organisation</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu @if(in_array($menu, ['adm_org-members', 'adm_org-details'])) {{'active'}} @endif" >
                    <li>
                        <a href="/admin/members" class="@if($menu == 'adm_org-members') active @endif"><i class="fa fa-user"></i>Members</a>
                    </li>
                    <li >
                        <a href="/admin/admin-organisation" class="@if($menu == 'adm_org-details') active @endif"><i class="fa fa-bank"></i>Details</a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="@if($menu == 'client-organisations') active @endif" href="/admin/client-organisations">
                    <i class="fa fa-building-o"></i>
                    <span>Client Organisations</span>
                </a>
            </li>
            <li>
                <a href="/admin/config" class="@if($menu == 'configuration') active @endif">
                    <i class="fa fa-cog"></i><span>Configuration</span>
                </a>
            </li>
        </ul>
    </section>
</aside>