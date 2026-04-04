@props(['collapsed' => false])

<aside id="user-sidebar"
       class="fixed top-0 left-0 z-40 h-screen bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transition-all duration-300"
       :style="{ width: sidebarCollapsed ? '85px' : '256px' }"
       x-data="userSidebarComponent()"
       x-init="init()"
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

                <!-- Shop Products -->
                <li class="relative">
                    <a href="{{ route('user.products.browse') }}"
                        id="products-link"
                        class="flex items-center p-2 rounded-lg transition-all duration-300 group"
                        :class="{
                            'justify-center': sidebarCollapsed,
                            'justify-start': !sidebarCollapsed,
                            'bg-[#1a2c3e] text-white': {{ request()->routeIs('user.products.*') ? 'true' : 'false' }},
                            'hover:bg-[#1a2c3e]/10 dark:hover:bg-[#1a2c3e]/20': !{{ request()->routeIs('user.products.*') ? 'true' : 'false' }}
                        }"
                        @mouseenter="showTooltip($event, 'Shop Products')"
                        @mouseleave="hideTooltip()"
                        @click="closeDropdown(); hideTooltip()">
                        <i class="bi bi-bag text-xl flex-shrink-0" 
                           :class="{ 'text-white': {{ request()->routeIs('user.products.*') ? 'true' : 'false' }}, 'text-[#1a2c3e] dark:text-[#1a2c3e]': !{{ request()->routeIs('user.products.*') ? 'true' : 'false' }} }"></i>
                        <span id="products-text" class="ml-3 text-sm font-medium"
                              :class="{ 
                                  'hidden': sidebarCollapsed,
                                  'inline': !sidebarCollapsed,
                                  'text-white': {{ request()->routeIs('user.products.*') ? 'true' : 'false' }},
                                  'text-gray-700 dark:text-gray-300': !{{ request()->routeIs('user.products.*') ? 'true' : 'false' }}
                              }">
                            Shop Products
                        </span>
                    </a>
                </li>

                <!-- My Orders -->
                <li class="relative">
                    <a href="{{ route('user.orders.index') }}"
                        id="orders-link"
                        class="flex items-center p-2 rounded-lg transition-all duration-300 group"
                        :class="{
                            'justify-center': sidebarCollapsed,
                            'justify-start': !sidebarCollapsed,
                            'bg-[#1a2c3e] text-white': {{ request()->routeIs('user.orders.*') ? 'true' : 'false' }},
                            'hover:bg-[#1a2c3e]/10 dark:hover:bg-[#1a2c3e]/20': !{{ request()->routeIs('user.orders.*') ? 'true' : 'false' }}
                        }"
                        @mouseenter="showTooltip($event, 'My Orders')"
                        @mouseleave="hideTooltip()"
                        @click="closeDropdown(); hideTooltip()">
                        <i class="bi bi-cart text-xl flex-shrink-0" 
                           :class="{ 'text-white': {{ request()->routeIs('user.orders.*') ? 'true' : 'false' }}, 'text-[#1a2c3e] dark:text-[#1a2c3e]': !{{ request()->routeIs('user.orders.*') ? 'true' : 'false' }} }"></i>
                        <span id="orders-text" class="ml-3 text-sm font-medium"
                              :class="{ 
                                  'hidden': sidebarCollapsed,
                                  'inline': !sidebarCollapsed,
                                  'text-white': {{ request()->routeIs('user.orders.*') ? 'true' : 'false' }},
                                  'text-gray-700 dark:text-gray-300': !{{ request()->routeIs('user.orders.*') ? 'true' : 'false' }}
                              }">
                            My Orders
                        </span>
                    </a>
                </li>

                <!-- General Heading -->
                <li id="general-heading" class="pt-4" 
                    :class="{ 'hidden': sidebarCollapsed, 'block': !sidebarCollapsed }">
                    <h2 class="text-xs uppercase tracking-wider font-semibold text-[#1a2c3e] dark:text-[#1a2c3e] px-2">Account</h2>
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
                                Regular User
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
                        <a href="{{ route('user.profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-[#1a2c3e]/10 dark:hover:bg-[#1a2c3e]/20 hover:text-[#1a2c3e] transition-colors duration-150">
                            <i class="bi bi-person-circle mr-3 text-[#1a2c3e]"></i>My Profile
                        </a>
                        <a href="{{ route('user.profile.edit') }}#password" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-[#1a2c3e]/10 dark:hover:bg-[#1a2c3e]/20 hover:text-[#1a2c3e] transition-colors duration-150">
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
function userSidebarComponent() {
    return {
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' || {{ $collapsed ? 'true' : 'false' }},
        isDropdownOpen: false,
        tooltipTimeout: null,
        currentTooltipItem: null,
        tooltipElement: null,
        dropdownClickHandler: null,
        
        init() {
            // Set initial width
            this.updateWidth();
            
            // Create tooltip element
            this.tooltipElement = document.createElement('div');
            this.tooltipElement.className = 'fixed px-3 py-1.5 bg-[#1a2c3e] text-white text-sm font-medium rounded-lg whitespace-nowrap shadow-xl z-[9999]';
            this.tooltipElement.style.display = 'none';
            this.tooltipElement.style.pointerEvents = 'none';
            document.body.appendChild(this.tooltipElement);
            
            // Save state to localStorage and update parent when it changes
            this.$watch('sidebarCollapsed', (value) => {
                localStorage.setItem('sidebarCollapsed', value);
                this.updateWidth();
                this.closeDropdown();
                this.hideTooltip();
                
                // Dispatch event to update main content margin
                const mainContent = document.querySelector('.flex-1.flex.flex-col');
                if (mainContent) {
                    mainContent.style.marginLeft = value ? '85px' : '256px';
                }
                
                // Dispatch event for parent
                const event = new CustomEvent('sidebar-toggle', { 
                    detail: { collapsed: value } 
                });
                window.dispatchEvent(event);
            });
            
            // Setup click away and navigation handlers
            this.setupClickAwayHandler();
            this.setupNavigationHandler();
            
            // Set initial margin
            const mainContent = document.querySelector('.flex-1.flex.flex-col');
            if (mainContent) {
                mainContent.style.marginLeft = this.sidebarCollapsed ? '85px' : '256px';
            }
            
            // Listen for sidebar toggle events from parent
            window.addEventListener('sidebar-toggle', (event) => {
                if (event.detail.collapsed !== undefined && event.detail.collapsed !== this.sidebarCollapsed) {
                    this.sidebarCollapsed = event.detail.collapsed;
                }
            });
            
            // Recalculate dropdown position on window resize
            window.addEventListener('resize', () => {
                if (this.isDropdownOpen) {
                    this.$nextTick(() => {
                        this.positionDropdown();
                    });
                }
            });
        },
        
        updateWidth() {
            const sidebar = document.getElementById('user-sidebar');
            if (sidebar) {
                sidebar.style.width = this.sidebarCollapsed ? '85px' : '256px';
            }
        },
        
        positionDropdown() {
            if (!this.isDropdownOpen) return;
            
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
        
        setupNavigationHandler() {
            const mainNavLinks = document.querySelectorAll('#user-sidebar > nav li:not(#user-dropdown-container) a[href]');
            mainNavLinks.forEach(link => {
                link.addEventListener('click', () => {
                    this.closeDropdown();
                    this.hideTooltip();
                });
            });
        },
        
        setupClickAwayHandler() {
            if (this.dropdownClickHandler) {
                document.removeEventListener('click', this.dropdownClickHandler);
            }
            
            this.dropdownClickHandler = (e) => {
                if (!this.isDropdownOpen) return;
                
                const userButton = document.getElementById('user-button');
                const userDropdown = document.getElementById('user-dropdown');
                const sidebar = document.getElementById('user-sidebar');
                
                if (!userButton || !userDropdown) return;
                
                if (userButton.contains(e.target) || userDropdown.contains(e.target)) {
                    return;
                }
                
                if (sidebar && sidebar.contains(e.target)) {
                    this.closeDropdown();
                    return;
                }
                
                this.closeDropdown();
            };
            
            document.addEventListener('click', this.dropdownClickHandler);
        },
        
        toggleSidebar() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
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
        }
    }
}
</script>

<style>
[x-cloak] { display: none !important; }

/* Smooth transitions */
#user-sidebar,
.flex-1.flex.flex-col {
    transition-property: width, margin-left;
    transition-duration: 0.3s;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Hover effects */
#user-sidebar .group:hover i {
    transform: scale(1.05);
    transition: transform 0.2s ease;
}

/* Dropdown animations */
.dropdown-menu {
    transition-property: opacity, visibility;
    transition-duration: 0.15s;
    transition-timing-function: ease-out;
}
</style>