@props(['collapsed' => false])

<aside id="default-sidebar"
       class="fixed top-0 left-0 z-40 h-screen bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transition-all duration-300"
       :style="{ width: sidebarCollapsed ? '85px' : '256px' }"
       x-data="sidebarComponent()"
       x-init="init()"
       x-show="true"
       x-cloak>
    
    <nav class="h-full flex flex-col bg-white dark:bg-gray-800">
        <!-- Header -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between gap-2">
                <div id="logo-container" class="flex items-center space-x-3" 
                     :class="{ 'justify-center w-full': sidebarCollapsed, 'justify-start': !sidebarCollapsed }">
                    <div class="flex-shrink-0">
                        <img src="{{ asset('images/logos.png') }}"
                              alt="HMR Logo"
                              class="w-10 h-10 object-contain">
                    </div>
                    <div id="logo-text" class="flex flex-col" 
                         :class="{ 'hidden': sidebarCollapsed, 'flex': !sidebarCollapsed }">
                        <span class="text-sm font-semibold text-[#1a2c3e] dark:text-[#1a2c3e] whitespace-nowrap">
                            Product Inventory
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            Management System
                        </span>
                    </div>
                </div>
                
                <button id="toggle-sidebar"
                        class="w-10 h-10 flex items-center justify-center rounded-lg hover:outline-2 hover:outline-[#1a2c3e] hover:bg-[#1a2c3e]/10 dark:hover:bg-[#1a2c3e]/20 transition-all duration-300 flex-shrink-0"
                        aria-label="Toggle sidebar"
                        @click="toggleSidebar()">
                    <i class="bi text-2xl text-[#1a2c3e]" 
                       :class="{ 'bi-x-lg': sidebarCollapsed, 'bi-list': !sidebarCollapsed }"></i>
                </button>
            </div>
        </div>

        <!-- Menu -->
        <div class="flex-1 px-3 py-4 overflow-y-auto">
            <ul class="space-y-2 font-medium">
                <!-- Main Menu Heading -->
                <li id="main-menu-heading" class="px-2 mb-1" 
                    :class="{ 'hidden': sidebarCollapsed, 'block': !sidebarCollapsed }">
                    <h2 class="text-xs uppercase tracking-wider font-semibold text-[#1a2c3e] dark:text-[#1a2c3e]">Main Menu</h2>
                </li>

                <!-- Dashboard -->
                <li class="relative">
                    <a href="{{ route('dashboard') }}"
                        id="dashboard-link"
                        class="flex items-center p-2 rounded-lg transition-all duration-300 group"
                        :class="{
                            'justify-center': sidebarCollapsed,
                            'justify-start': !sidebarCollapsed,
                            'bg-[#1a2c3e] text-white': {{ request()->routeIs('dashboard') ? 'true' : 'false' }},
                            'hover:bg-[#1a2c3e]/10 dark:hover:bg-[#1a2c3e]/20': !{{ request()->routeIs('dashboard') ? 'true' : 'false' }}
                        }"
                        @mouseenter="showTooltip($event, 'Dashboard')"
                        @mouseleave="hideTooltip()"
                        @click="closeDropdown(); hideTooltip()">
                        <i class="bi bi-speedometer2 text-xl flex-shrink-0" 
                           :class="{ 'text-white': {{ request()->routeIs('dashboard') ? 'true' : 'false' }}, 'text-[#1a2c3e] dark:text-[#1a2c3e]': !{{ request()->routeIs('dashboard') ? 'true' : 'false' }} }"></i>
                        <span id="dashboard-text" class="ml-3 text-sm font-medium"
                              :class="{ 
                                  'hidden': sidebarCollapsed,
                                  'inline': !sidebarCollapsed,
                                  'text-white': {{ request()->routeIs('dashboard') ? 'true' : 'false' }},
                                  'text-gray-700 dark:text-gray-300': !{{ request()->routeIs('dashboard') ? 'true' : 'false' }}
                              }">
                            Dashboard
                        </span>
                    </a>
                </li>

                <!-- Products -->
                <li class="relative">
                    <a href="{{ route('products.index') }}"
                        id="products-link"
                        class="flex items-center p-2 rounded-lg transition-all duration-300 group"
                        :class="{
                            'justify-center': sidebarCollapsed,
                            'justify-start': !sidebarCollapsed,
                            'bg-[#1a2c3e] text-white': {{ request()->routeIs('products.*') ? 'true' : 'false' }},
                            'hover:bg-[#1a2c3e]/10 dark:hover:bg-[#1a2c3e]/20': !{{ request()->routeIs('products.*') ? 'true' : 'false' }}
                        }"
                        @mouseenter="showTooltip($event, 'Products')"
                        @mouseleave="hideTooltip()"
                        @click="closeDropdown(); hideTooltip()">
                        <i class="bi bi-box text-xl flex-shrink-0" 
                           :class="{ 'text-white': {{ request()->routeIs('products.*') ? 'true' : 'false' }}, 'text-[#1a2c3e] dark:text-[#1a2c3e]': !{{ request()->routeIs('products.*') ? 'true' : 'false' }} }"></i>
                        <span id="products-text" class="ml-3 text-sm font-medium"
                              :class="{ 
                                  'hidden': sidebarCollapsed,
                                  'inline': !sidebarCollapsed,
                                  'text-white': {{ request()->routeIs('products.*') ? 'true' : 'false' }},
                                  'text-gray-700 dark:text-gray-300': !{{ request()->routeIs('products.*') ? 'true' : 'false' }}
                              }">
                            Products
                        </span>
                    </a>
                </li>

                <!-- Categories -->
                <li class="relative">
                    <a href="{{ route('admin.categories.index') }}"
                        id="categories-link"
                        class="flex items-center p-2 rounded-lg transition-all duration-300 group"
                        :class="{
                            'justify-center': sidebarCollapsed,
                            'justify-start': !sidebarCollapsed,
                            'bg-[#1a2c3e] text-white': {{ request()->routeIs('admin.categories.*') ? 'true' : 'false' }},
                            'hover:bg-[#1a2c3e]/10 dark:hover:bg-[#1a2c3e]/20': !{{ request()->routeIs('admin.categories.*') ? 'true' : 'false' }}
                        }"
                        @mouseenter="showTooltip($event, 'Categories')"
                        @mouseleave="hideTooltip()"
                        @click="closeDropdown(); hideTooltip()">
                        <i class="bi bi-tags text-xl flex-shrink-0" 
                           :class="{ 'text-white': {{ request()->routeIs('admin.categories.*') ? 'true' : 'false' }}, 'text-[#1a2c3e] dark:text-[#1a2c3e]': !{{ request()->routeIs('admin.categories.*') ? 'true' : 'false' }} }"></i>
                        <span id="categories-text" class="ml-3 text-sm font-medium"
                              :class="{ 
                                  'hidden': sidebarCollapsed,
                                  'inline': !sidebarCollapsed,
                                  'text-white': {{ request()->routeIs('admin.categories.*') ? 'true' : 'false' }},
                                  'text-gray-700 dark:text-gray-300': !{{ request()->routeIs('admin.categories.*') ? 'true' : 'false' }}
                              }">
                            Categories
                        </span>
                    </a>
                </li>

                <!-- Orders -->
                <li class="relative">
                    <a href="{{ route('admin.orders.index') }}"
                        id="orders-link"
                        class="flex items-center p-2 rounded-lg transition-all duration-300 group"
                        :class="{
                            'justify-center': sidebarCollapsed,
                            'justify-start': !sidebarCollapsed,
                            'bg-[#1a2c3e] text-white': {{ request()->routeIs('admin.orders.*') ? 'true' : 'false' }},
                            'hover:bg-[#1a2c3e]/10 dark:hover:bg-[#1a2c3e]/20': !{{ request()->routeIs('admin.orders.*') ? 'true' : 'false' }}
                        }"
                        @mouseenter="showTooltip($event, 'Orders')"
                        @mouseleave="hideTooltip()"
                        @click="closeDropdown(); hideTooltip()">
                        <i class="bi bi-cart text-xl flex-shrink-0" 
                           :class="{ 'text-white': {{ request()->routeIs('admin.orders.*') ? 'true' : 'false' }}, 'text-[#1a2c3e] dark:text-[#1a2c3e]': !{{ request()->routeIs('admin.orders.*') ? 'true' : 'false' }} }"></i>
                        <span id="orders-text" class="ml-3 text-sm font-medium"
                              :class="{ 
                                  'hidden': sidebarCollapsed,
                                  'inline': !sidebarCollapsed,
                                  'text-white': {{ request()->routeIs('admin.orders.*') ? 'true' : 'false' }},
                                  'text-gray-700 dark:text-gray-300': !{{ request()->routeIs('admin.orders.*') ? 'true' : 'false' }}
                              }">
                            Orders
                        </span>
                    </a>
                </li>

                <!-- Users -->
                <li class="relative">
                    <a href="{{ route('admin.users.index') }}"
                        id="users-link"
                        class="flex items-center p-2 rounded-lg transition-all duration-300 group"
                        :class="{
                            'justify-center': sidebarCollapsed,
                            'justify-start': !sidebarCollapsed,
                            'bg-[#1a2c3e] text-white': {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }},
                            'hover:bg-[#1a2c3e]/10 dark:hover:bg-[#1a2c3e]/20': !{{ request()->routeIs('admin.users.*') ? 'true' : 'false' }}
                        }"
                        @mouseenter="showTooltip($event, 'Users')"
                        @mouseleave="hideTooltip()"
                        @click="closeDropdown(); hideTooltip()">
                        <i class="bi bi-people text-xl flex-shrink-0" 
                           :class="{ 'text-white': {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }}, 'text-[#1a2c3e] dark:text-[#1a2c3e]': !{{ request()->routeIs('admin.users.*') ? 'true' : 'false' }} }"></i>
                        <span id="users-text" class="ml-3 text-sm font-medium"
                              :class="{ 
                                  'hidden': sidebarCollapsed,
                                  'inline': !sidebarCollapsed,
                                  'text-white': {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }},
                                  'text-gray-700 dark:text-gray-300': !{{ request()->routeIs('admin.users.*') ? 'true' : 'false' }}
                              }">
                            Users
                        </span>
                    </a>
                </li>

                <!-- General Heading -->
                <li id="general-heading" class="pt-4" 
                    :class="{ 'hidden': sidebarCollapsed, 'block': !sidebarCollapsed }">
                    <h2 class="text-xs uppercase tracking-wider font-semibold text-[#1a2c3e] dark:text-[#1a2c3e] px-2">General</h2>
                </li>

                <!-- User Profile Item -->
                <li class="relative" id="user-dropdown-container">
                    <button id="user-button"
                            type="button"
                            class="w-full flex items-center p-2 rounded-lg transition-all duration-300 hover:bg-[#1a2c3e]/10 dark:hover:bg-[#1a2c3e]/20 group"
                            :class="{ 'justify-center': sidebarCollapsed, 'justify-start': !sidebarCollapsed }"
                            @click.stop="toggleDropdown()"
                            @mouseenter="sidebarCollapsed && !isDropdownOpen ? showTooltip($event, '{{ Auth::user()->name }}') : null"
                            @mouseleave="hideTooltip()">
                        @php $initial = strtoupper(substr(Auth::user()->name, 0, 1)); @endphp
                        @if(Auth::user()->profile_photo_path)
                            <img class="w-10 h-10 rounded-full object-cover border-2 border-[#1a2c3e]/20 shadow-lg flex-shrink-0"
                                  src="{{ Storage::url(Auth::user()->profile_photo_path) }}"
                                 alt="Profile Picture">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-[#1a2c3e] to-[#2c4a6e] flex items-center justify-center text-white font-semibold shadow-lg flex-shrink-0">
                                {{ $initial }}
                            </div>
                        @endif

                        <div id="user-info" class="ml-3 flex-1 text-left" 
                             :class="{ 'hidden': sidebarCollapsed, 'block': !sidebarCollapsed }">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="text-xs text-[#1a2c3e] dark:text-[#1a2c3e]">
                                {{ Auth::user()->email }}
                            </p>
                        </div>
                        <i id="dropdown-icon" class="bi bi-chevron-down text-[#1a2c3e] transition-transform duration-200 ml-auto"
                           :class="{ 
                               'hidden': sidebarCollapsed,
                               'inline-block': !sidebarCollapsed,
                               'rotate-180': isDropdownOpen
                           }"></i>
                    </button>
                    
                    <div id="user-dropdown" 
                         class="dropdown-menu absolute bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 py-2 z-[9998] transition-opacity duration-150"
                         :style="dropdownStyle"
                         :class="{ 'opacity-0 pointer-events-none': !isDropdownOpen, 'opacity-100': isDropdownOpen }"
                         x-show="isDropdownOpen"
                         x-cloak>
                        <a href="{{ route('admin.profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-[#1a2c3e]/10 dark:hover:bg-[#1a2c3e]/20 hover:text-[#1a2c3e] transition-colors duration-150">
                            <i class="bi bi-person-circle mr-3 text-[#1a2c3e]"></i>My Profile
                        </a>
                        <a href="{{ route('admin.profile.password') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-[#1a2c3e]/10 dark:hover:bg-[#1a2c3e]/20 hover:text-[#1a2c3e] transition-colors duration-150">
                            <i class="bi bi-key mr-3 text-[#1a2c3e]"></i>Change Password
                        </a>
                        <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-[#1a2c3e]/10 dark:hover:bg-[#1a2c3e]/20 hover:text-[#1a2c3e] transition-colors duration-150">
                                <i class="bi bi-box-arrow-right mr-3 text-[#1a2c3e]"></i>Logout
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</aside>

<script>
function sidebarComponent() {
    return {
        isDropdownOpen: false,
        tooltipTimeout: null,
        currentTooltipItem: null,
        tooltipElement: null,
        dropdownClickHandler: null,
        
        init() {
            // Create tooltip element
            this.tooltipElement = document.createElement('div');
            this.tooltipElement.className = 'fixed px-3 py-1.5 bg-[#1a2c3e] text-white text-sm font-medium rounded-lg whitespace-nowrap shadow-xl z-[9999]';
            this.tooltipElement.style.display = 'none';
            this.tooltipElement.style.pointerEvents = 'none';
            document.body.appendChild(this.tooltipElement);
            
            // Listen for sidebar toggle events from parent
            window.addEventListener('sidebar-toggle', (event) => {
                if (event.detail.collapsed !== undefined) {
                    this.closeDropdown();
                    this.hideTooltip();
                }
            });
            
            // Setup click-away handler
            this.setupClickAwayHandler();
        },

        setupClickAwayHandler() {
            if (this.dropdownClickHandler) {
                document.removeEventListener('click', this.dropdownClickHandler);
            }

            this.dropdownClickHandler = (e) => {
                if (!this.isDropdownOpen) return;

                const userButton = document.getElementById('user-button');
                const userDropdown = document.getElementById('user-dropdown');
                
                if (!userButton || !userDropdown) return;

                if (userButton.contains(e.target) || userDropdown.contains(e.target)) {
                    return;
                }

                this.closeDropdown();
            };

            document.addEventListener('click', this.dropdownClickHandler);
        },
        
        toggleSidebar() {
            const event = new CustomEvent('sidebar-toggle', { 
                detail: { collapsed: !this.sidebarCollapsed } 
            });
            window.dispatchEvent(event);
            this.closeDropdown();
            this.hideTooltip();
        },
        
        toggleDropdown() {
            if (this.isDropdownOpen) {
                this.closeDropdown();
            } else {
                this.openDropdown();
            }
        },
        
        openDropdown() {
            this.isDropdownOpen = true;
            this.hideTooltip();
            this.$nextTick(() => {
                this.positionDropdown();
            });
        },
        
        positionDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            const button = document.getElementById('user-button');
            
            if (!dropdown || !button) return;
            
            if (this.sidebarCollapsed) {
                const rect = button.getBoundingClientRect();
                dropdown.style.position = 'fixed';
                dropdown.style.left = (rect.right + 8) + 'px';
                dropdown.style.top = rect.top + 'px';
                dropdown.style.minWidth = '200px';
                dropdown.style.right = 'auto';
                dropdown.style.marginTop = '0';
            } else {
                dropdown.style.position = 'absolute';
                dropdown.style.left = '1rem';
                dropdown.style.right = '1rem';
                dropdown.style.top = '100%';
                dropdown.style.marginTop = '0.5rem';
                dropdown.style.minWidth = 'auto';
            }
        },
        
        closeDropdown() {
            this.isDropdownOpen = false;
        },
        
        get dropdownStyle() {
            return {};
        },
        
        showTooltip(event, text) {
            if (!this.sidebarCollapsed) return;
            
            if (this.tooltipTimeout) {
                clearTimeout(this.tooltipTimeout);
            }
            
            if (this.isDropdownOpen) return;
            
            const element = event.currentTarget;
            if (this.currentTooltipItem === element) return;
            
            this.tooltipTimeout = setTimeout(() => {
                const rect = element.getBoundingClientRect();
                this.tooltipElement.textContent = text;
                this.tooltipElement.style.display = 'block';
                this.tooltipElement.style.left = (rect.right + 8) + 'px';
                this.tooltipElement.style.top = (rect.top + rect.height / 2 - 20) + 'px';
                this.currentTooltipItem = element;
            }, 300);
        },
        
        hideTooltip() {
            if (this.tooltipTimeout) {
                clearTimeout(this.tooltipTimeout);
                this.tooltipTimeout = null;
            }
            if (this.tooltipElement) {
                this.tooltipElement.style.display = 'none';
            }
            this.currentTooltipItem = null;
        },
        
        destroy() {
            if (this.dropdownClickHandler) {
                document.removeEventListener('click', this.dropdownClickHandler);
            }
            if (this.tooltipElement) {
                this.tooltipElement.remove();
            }
        }
    }
}

// Add Alpine.js directive for handling route checking
window.request = function() {
    return {
        routeIs: function(route) {
            const currentPath = window.location.pathname;
            if (route === 'dashboard') {
                return currentPath === '/dashboard' || currentPath === '/';
            }
            if (route === 'products.*') {
                return currentPath.startsWith('/products');
            }
            if (route === 'orders.*') {
                return currentPath.startsWith('/orders');
            }
            if (route === 'admin.categories.*') {
                return currentPath.startsWith('/admin/categories');
            }
            if (route === 'admin.users.*') {
                return currentPath.startsWith('/admin/users');
            }
            return false;
        }
    };
};

// Handle page load to ensure sidebar state is consistent
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('default-sidebar');
    if (sidebar) {
        sidebar.style.transform = 'none';
        sidebar.style.transition = 'none';
        setTimeout(() => {
            sidebar.style.transition = '';
        }, 10);
    }
    
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            setTimeout(() => {
                const event = new CustomEvent('sidebar-toggle', { 
                    detail: { collapsed: localStorage.getItem('sidebarCollapsed') === 'true' } 
                });
                window.dispatchEvent(event);
                
                if (sidebar) {
                    const allLinks = sidebar.querySelectorAll('a');
                    allLinks.forEach(l => {
                        l.style.pointerEvents = '';
                    });
                }
            }, 100);
        }
    });
});
</script>

<style>
[x-cloak] { display: none !important; }
</style>