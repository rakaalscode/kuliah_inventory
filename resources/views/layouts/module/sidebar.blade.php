<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li><a href="#"> <i class="fa fa-dashboard"></i> <span>Dashboard</span> </a>
        </li>
        <li class="treeview">
            <a href="#">
            <i class="fa fa-home"></i>
            <span>Inventory</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{route('products.index')}}"><i class="fa fa-circle-o"></i> Products</a></li>
                <li><a href="{{route('categories.index')}}"><i class="fa fa-circle-o"></i> Categories</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
            <i class="fa fa-users"></i>
            <span>Manage Users</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{route('customers.index')}}"><i class="fa fa-circle-o"></i> Customers</a></li>
                <li><a href="{{route('sales.index')}}"><i class="fa fa-circle-o"></i> Sales</a></li>
                <li><a href="{{route('suppliers.index')}}"><i class="fa fa-circle-o"></i> Suppiler</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
            <i class="fa fa-arrows-h"></i>
            <span>Transactions</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{route('productins.index')}}"><i class="fa fa-circle-o"></i> Product Ins</a></li>
                <li><a href="{{route('productouts.index')}}"><i class="fa fa-circle-o"></i> Product Out</a></li>
            </ul>
        </li>
        </ul>
    </section>
</aside>