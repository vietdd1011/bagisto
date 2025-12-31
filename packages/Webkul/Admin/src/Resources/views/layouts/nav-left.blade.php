@php

    $tree = \Webkul\Core\Tree::create();

    foreach (config('core') as $item) {
        $tree->add($item);
    }

    $tree->items = core()->sortItems($tree->items);

    $config = $tree;

    $allLocales = core()->getAllLocales()->pluck('name', 'code');
@endphp

<div class="navbar-left" v-bind:class="{'open': isMenuOpen}">

    <ul class="menubar">
        @foreach ($menu->items as $menuItem)
        <li class="menu-item {{ $menu->getActive($menuItem) }}">
            <a class="menubar-anchor"  href="{{ $menuItem['url'] }}">
                <span class="icon-menu icon {{ $menuItem['icon-class'] }}"></span>

                <span class="menu-label">{{ trans($menuItem['name']) }}</span>

                @if(count($menuItem['children']) || $menuItem['key'] == 'configuration' )
                    <span
                        class="icon arrow-icon {{ $menu->getActive($menuItem) == 'active' ? 'rotate-arrow-icon' : '' }} {{ ( core()->getCurrentLocale() && core()->getCurrentLocale()->direction == 'rtl' ) ? 'arrow-icon-right' :'arrow-icon-left' }}"
                        ></span>

                @endif
            </a>
            @if ($menuItem['key'] != 'configuration')
                @if (count($menuItem['children']))
                    <ul class="sub-menubar">
                        @foreach ($menuItem['children'] as $subMenuItem)
                            <li class="sub-menu-item {{ $menu->getActive($subMenuItem) }}">
                                <a href="{{ count($subMenuItem['children']) ? current($subMenuItem['children'])['url'] : $subMenuItem['url'] }}">
                                    <span class="menu-label">{{ trans($subMenuItem['name']) }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            @else
                <ul class="sub-menubar">
                    @foreach ($config->items as $key => $item)
                        <li class="sub-menu-item {{ $item['key'] == request()->route('slug') ? 'active' : '' }}">
                            <a href="{{ route('admin.configuration.index', $item['key']) }}">
                                <span class="menu-label"> {{ isset($item['name']) ? trans($item['name']) : '' }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
        @endforeach
    </ul>

    <nav-slide-button id="nav-expand-button" icon-class="accordian-right-icon"></nav-slide-button>
</div>

@push('scripts')

    <script>

        $(document).ready(function () {
            // Existing click functionality
            $(".menubar-anchor").click(function() {
                if ( $(this).parent().attr('class') == 'menu-item active' ) {
                    $(this).parent().removeClass('active');
                    $('.arrow-icon-left').removeClass('rotate-arrow-icon');
                    $('.arrow-icon-right').removeClass('rotate-arrow-icon');
                    $(".sub-menubar").hide();
                    event.preventDefault();
                }
            });

            // Enhanced hover functionality for menu items with submenus
            $(".menu-item").hover(
                function() {
                    // Mouse enter
                    var $menuItem = $(this);
                    var $submenu = $menuItem.find('.sub-menubar');
                    
                    if ($submenu.length > 0) {
                        clearTimeout($menuItem.data('hideTimer'));
                        $submenu.stop(true, true).slideDown(200);
                        $menuItem.addClass('hover-active');
                        $menuItem.find('.arrow-icon').addClass('rotate-arrow-icon');
                    }
                },
                function() {
                    // Mouse leave
                    var $menuItem = $(this);
                    var $submenu = $menuItem.find('.sub-menubar');
                    
                    if ($submenu.length > 0) {
                        var hideTimer = setTimeout(function() {
                            $submenu.stop(true, true).slideUp(200);
                            $menuItem.removeClass('hover-active');
                            $menuItem.find('.arrow-icon').removeClass('rotate-arrow-icon');
                        }, 200); // Delay to allow moving to submenu
                        
                        $menuItem.data('hideTimer', hideTimer);
                    }
                }
            );

            // Keep submenu open when hovering over it
            $(".sub-menubar").hover(
                function() {
                    // Mouse enter submenu
                    var $menuItem = $(this).closest('.menu-item');
                    clearTimeout($menuItem.data('hideTimer'));
                },
                function() {
                    // Mouse leave submenu
                    var $menuItem = $(this).closest('.menu-item');
                    var $submenu = $(this);
                    
                    setTimeout(function() {
                        if (!$menuItem.is(':hover') && !$submenu.is(':hover')) {
                            $submenu.stop(true, true).slideUp(200);
                            $menuItem.removeClass('hover-active');
                            $menuItem.find('.arrow-icon').removeClass('rotate-arrow-icon');
                        }
                    }, 100);
                }
            );
        });

    </script>

    <style>
        /* Enhanced hover effects for menu */
        .navbar-left .menubar .menu-item {
            transition: background-color 0.2s ease;
        }
        
        .navbar-left .menubar .menu-item.hover-active,
        .navbar-left .menubar .menu-item:hover {
            background-color: #f8f9fa !important;
            overflow: visible !important;
        }
        
        .navbar-left .menubar .menu-item .sub-menubar {
            transition: all 0.2s ease;
            border-radius: 0 6px 6px 0;
            box-shadow: 2px 2px 8px rgba(0,0,0,0.1);
        }
        
        .navbar-left .menubar .menu-item.hover-active .sub-menubar,
        .navbar-left .menubar .menu-item:hover .sub-menubar {
            display: block !important;
        }
        
        .navbar-left .menubar .menu-item .sub-menubar .sub-menu-item {
            transition: background-color 0.15s ease;
        }
        
        .navbar-left .menubar .menu-item .sub-menubar .sub-menu-item:hover {
            background-color: #e9ecef;
        }
        
        .navbar-left .menubar .menu-item .sub-menubar .sub-menu-item:hover a {
            color: #495057 !important;
        }

        /* Create a bridge area to prevent menu disappearing */
        .navbar-left .menubar .menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            right: -5px;
            width: 5px;
            height: 100%;
            background: transparent;
            z-index: 999;
        }
    </style>

@endpush