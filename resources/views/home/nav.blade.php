<style>

    /*
    |--------------------------------------------------------------------------
    | Kaira Professional Navbar
    |--------------------------------------------------------------------------
    */

    .kaira-header {
        position: relative;
        z-index: 1050;
        width: 100%;
        background-color: #ffffff;
    }

    .kaira-navbar {
        min-height: 96px;
        padding: 16px 0;
        border-bottom: 1px solid #ededed;
        background-color: rgba(255, 255, 255, 0.98);
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.04);
    }

    .kaira-navbar.is-sticky {
        position: fixed;
        top: 0;
        right: 0;
        left: 0;
        min-height: 76px;
        padding: 8px 0;
        animation: navbarSlideDown 0.3s ease;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.10);
    }

    @keyframes navbarSlideDown {

        from {
            opacity: 0;
            transform: translateY(-100%);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Brand
    |--------------------------------------------------------------------------
    */

    .kaira-brand {
        display: inline-flex;
        align-items: center;
        color: #171717;
        font-family: Georgia, "Times New Roman", serif;
        font-size: 31px;
        font-weight: 700;
        letter-spacing: 5px;
        line-height: 1;
        text-decoration: none;
        text-transform: uppercase;
    }

    .kaira-brand:hover {
        color: #171717;
    }

    .kaira-brand-dot {
        margin-left: 2px;
        color: #cf2e3b;
        font-size: 31px;
    }

    /*
    |--------------------------------------------------------------------------
    | Navigation Links
    |--------------------------------------------------------------------------
    */

    .kaira-navigation {
        gap: 32px;
    }

    .kaira-navigation .nav-link {
        position: relative;
        padding: 12px 0 !important;
        color: #333333;
        font-size: 15px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .kaira-navigation .nav-link:hover,
    .kaira-navigation .nav-link.active {
        color: #111111;
    }

    .kaira-navigation .nav-link::after {
        position: absolute;
        right: 0;
        bottom: 3px;
        left: 0;
        width: 0;
        height: 2px;
        margin: auto;
        background-color: #222222;
        content: "";
        transition: width 0.25s ease;
    }

    .kaira-navigation .nav-link:hover::after,
    .kaira-navigation .nav-link.active::after {
        width: 100%;
    }

    /*
    |--------------------------------------------------------------------------
    | Navbar Buttons
    |--------------------------------------------------------------------------
    */

    .kaira-navbar-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .kaira-icon-button {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 46px;
        height: px;
        padding: 0;
        border: 0;
        border-radius: 50%;
        background-color: transparent;
        color: #222222;
        text-decoration: none;
        cursor: pointer;
        transition:
            color 0.25s ease,
            background-color 0.25s ease,
            transform 0.25s ease;
    }

    .kaira-icon-button:hover {
        background-color: #f3f3f3;
        color: #cf2e3b;
        transform: translateY(-2px);
    }

    .kaira-icon-button svg {
        width: 25px;
        height: 25px;
        stroke: currentColor;
    }

    .kaira-cart-count {
        position: absolute;
        top: -2px;
        right: -2px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 21px;
        height: 21px;
        padding: 0 5px;
        border: 2px solid #ffffff;
        border-radius: 50px;
        background-color: #cf2e3b;
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
    }

    /*
    |--------------------------------------------------------------------------
    | Mobile Toggle
    |--------------------------------------------------------------------------
    */

    .kaira-navbar-toggler {
        display: flex;
        flex-direction: column;
        gap: 5px;
               padding: 8px;
        border: 0;
        background: transparent;
        box-shadow: none !important;
    }

    .kaira-navbar-toggler span {
        display: block;
        width: 25px;
               height: 2px;
        background-color: #222222;
        transition: 0.25s ease;
    }

    /*
    |--------------------------------------------------------------------------
    | Search Panel
    |--------------------------------------------------------------------------
    */

    .kaira-search-panel {
        position: absolute;
        top: 100%;
        right: 0;
        left: 0;
        display: none;
        padding: 25px 0;
        border-bottom: 1px solid #ededed;
        background-color: #ffffff;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.10);
    }

    .kaira-search-panel.show {
        display: block;
        animation: searchPanelOpen 0.25s ease;
    }

    @keyframes searchPanelOpen {

        from {
            opacity: 0;
            transform: translateY(-12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }

    }

    .kaira-search-form {
        position: relative;
        max-width: 850px;
        margin: auto;
    }

    .kaira-search-input {
        width: 100%;
        height: 58px;
        padding: 0 150px 0 55px;
        border: 1px solid #d7d7d7;
        border-radius: 50px;
        background-color: #ffffff;
        color: #222222;
        font-size: 16px;
        outline: none;
        transition:
            border-color 0.25s ease,
            box-shadow 0.25s ease;
    }

    .kaira-search-input:focus {
        border-color: #222222;
        box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.05);
    }

    .kaira-search-input::placeholder {
        color: #999999;
    }

    .kaira-search-icon {
        position: absolute;
        top: 50%;
        left: 20px;
        width: 22px;
        height: 22px;
        pointer-events: none;
        stroke: #777777;
        transform: translateY(-50%);
    }

    .kaira-search-submit {
        position: absolute;
        top: 6px;
        right: 6px;
        height: 46px;
        padding: 0 30px;
        border: 0;
        border-radius: 50px;
        background-color: #222222;
        color: #ffffff;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.25s ease;
    }

    .kaira-search-submit:hover {
        background-color: #cf2e3b;
    }

    .kaira-search-close {
        position: absolute;
        top: 50%;
        right: -55px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        padding: 0;
        border: 0;
        border-radius: 50%;
        background-color: #f1f1f1;
        color: #222222;
        font-size: 25px;
        cursor: pointer;
        transform: translateY(-50%);
        transition: 0.25s ease;
    }

    .kaira-search-close:hover {
        background-color: #222222;
        color: #ffffff;
    }

    /*
    |--------------------------------------------------------------------------
    | Search Overlay
    |--------------------------------------------------------------------------
    */

    .kaira-search-overlay {
        position: fixed;
        z-index: 1040;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        display: none;
        background-color: rgba(0, 0, 0, 0.35);
        backdrop-filter: blur(2px);
    }

    .kaira-search-overlay.show {
        display: block;
    }

    /*
    |--------------------------------------------------------------------------
    | Responsive
    |--------------------------------------------------------------------------
    */

    @media (max-width: 1199px) {

        .kaira-navigation {
            gap: 20px;
        }

    }

    @media (max-width: 991px) {

        .kaira-navbar {
            min-height: 78px;
        }

        .kaira-brand {
            font-size: 26px;
        }

        .kaira-navbar .navbar-collapse {
            margin-top: 16px;
            padding: 20px;
            border-top: 1px solid #eeeeee;
            background-color: #ffffff;
        }

        .kaira-navigation {
            gap: 0;
        }

        .kaira-navigation .nav-link {
            display: inline-block;
            margin-bottom: 7px;
        }

        .kaira-navbar-actions {
            margin-top: 12px;
        }

        .kaira-search-panel {
            position: fixed;
            top: 78px;
            padding: 20px 15px;
        }

        .kaira-search-close {
            display: none;
        }

    }

    @media (max-width: 576px) {

        .kaira-brand {
            font-size: 22px;
            letter-spacing: 3px;
        }

        .kaira-search-input {
            height: 54px;
            padding-right: 105px;
            padding-left: 47px;
            font-size: 13px;
        }

        .kaira-search-icon {
            left: 16px;
            width: 20px;
            height: 20px;
        }

        .kaira-search-submit {
            top: 5px;
            right: 5px;
            height: 44px;
            padding: 0 18px;
            font-size: 13px;
        }

    }

</style>


@php
    /*
     * Calculate cart item quantity.
     */
    $kairaCart = session('cart', []);
    $kairaCartCount = 0;

    if (is_array($kairaCart)) {
        foreach ($kairaCart as $cartItem) {
            $kairaCartCount += isset($cartItem['quantity'])
                ? (int) $cartItem['quantity']
                : 1;
        }
    }
@endphp


<header
    class="kaira-header"
    id="kairaHeader"
>

    <nav
        class="navbar navbar-expand-lg kaira-navbar"
        id="kairaNavbar"
    >

        <div class="container">

            {{-- Brand --}}
            <a
                href="{{ route('home') }}"
                class="kaira-brand"
            >
                Kaira<span class="kaira-brand-dot">.</span>
            </a>


            {{-- Mobile Toggle --}}
            <button
                class="navbar-toggler kaira-navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#kairaNavbarMenu"
                aria-controls="kairaNavbarMenu"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span></span>
                <span></span>
                <span></span>
            </button>


            <div
                class="collapse navbar-collapse"
                id="kairaNavbarMenu"
            >

                <ul class="navbar-nav kaira-navigation mx-auto">

                    <li class="nav-item">

                        <a
                            href="{{ route('home') }}"
                            class="nav-link {{
                                request()->routeIs('home')
                                    ? 'active'
                                    : ''
                            }}"
                        >
                            Home
                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            href="{{ route('productss') }}"
                            class="nav-link {{
                                request()->routeIs(
                                    'shop.category',
                                    'shop.product'
                                )
                                    ? 'active'
                                    : ''
                            }}"
                        >
                            Shop
                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            href="{{ route('collections') }}"
                            class="nav-link {{
                                request()->routeIs(
                                    'collections',
                                    'collection.details',
                                    'collection-pro.details'
                                )
                                    ? 'active'
                                    : ''
                            }}"
                        >
                            Collections
                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            href="{{ route('productss') }}"
                            class="nav-link {{
                                request()->routeIs(
                                    'productss',
                                    'product.details'
                                )
                                    ? 'active'
                                    : ''
                            }}"
                        >
                            Products
                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            href="{{ route('home.blogs') }}"
                            class="nav-link {{
                                request()->routeIs(
                                    'home.blogs',
                                    'home.blog.details'
                                )
                                    ? 'active'
                                    : ''
                            }}"
                        >
                            Blogs
                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            href="{{ route('home.contact') }}"
                            class="nav-link {{
                                request()->routeIs(
                                    'home.contact'
                                )
                                    ? 'active'
                                    : ''
                            }}"
                        >
                            Contact
                        </a>

                    </li>

                </ul>


                {{-- Actions --}}
                <div class="kaira-navbar-actions">

                    {{-- Search --}}
                    <button
                        type="button"
                        class="kaira-icon-button"
                        id="kairaSearchOpen"
                        aria-label="Open search"
                        aria-expanded="false"
                        title="Search"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <circle
                                cx="11"
                                cy="11"
                                r="7"
                            ></circle>

                            <path
                                d="M20 20L16.5 16.5"
                            ></path>
                        </svg>
                    </button>


                    {{-- Cart --}}
                    <a
                        href="{{ route('cart') }}"
                        class="kaira-icon-button"
                        aria-label="Open shopping cart"
                        title="Cart"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path
                                d="M6 8H18L19 21H5L6 8Z"
                            ></path>

                            <path
                                d="M9 8V6C9 4.34 10.34 3 12 3C13.66 3 15 4.34 15 6V8"
                            ></path>
                        </svg>

                        @if($kairaCartCount > 0)

                            <span class="kaira-cart-count">
                                {{ $kairaCartCount }}
                            </span>

                        @endif

                    </a>

                </div>

            </div>

        </div>

    </nav>


    {{-- Expandable Search Panel --}}
    <div
        class="kaira-search-panel"
        id="kairaSearchPanel"
        aria-hidden="true"
    >

        <div class="container">

            <form
                action="{{ route('search') }}"
                method="GET"
                class="kaira-search-form"
                role="search"
            >

                <svg
                    class="kaira-search-icon"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <circle
                        cx="11"
                        cy="11"
                        r="7"
                    ></circle>

                    <path
                        d="M20 20L16.5 16.5"
                    ></path>
                </svg>

                <input
                    type="search"
                    name="search"
                    id="kairaSearchInput"
                    value="{{ request('search') }}"
                    class="kaira-search-input"
                    placeholder="Search products, collections, categories or blogs..."
                    autocomplete="off"
                    required
                >

                <button
                    type="submit"
                    class="kaira-search-submit"
                >
                    Search
                </button>

                <button
                    type="button"
                    class="kaira-search-close"
                    id="kairaSearchClose"
                    aria-label="Close search"
                >
                    &times;
                </button>

            </form>

        </div>

    </div>

</header>


{{-- Background overlay --}}
<div
    class="kaira-search-overlay"
    id="kairaSearchOverlay"
></div>


<script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const header =
                document.getElementById('kairaHeader');

            const navbar =
                document.getElementById('kairaNavbar');

            const searchOpenButton =
                document.getElementById('kairaSearchOpen');

            const searchCloseButton =
                document.getElementById('kairaSearchClose');

            const searchPanel =
                document.getElementById('kairaSearchPanel');

            const searchOverlay =
                document.getElementById('kairaSearchOverlay');

            const searchInput =
                document.getElementById('kairaSearchInput');

            const navbarMenu =
                document.getElementById('kairaNavbarMenu');


            /*
            |--------------------------------------------------------------------------
            | Open Search
            |--------------------------------------------------------------------------
            */

            function openSearchPanel() {

                if (!searchPanel) {
                    return;
                }

                searchPanel.classList.add('show');
                searchOverlay.classList.add('show');

                searchPanel.setAttribute(
                    'aria-hidden',
                    'false'
                );

                searchOpenButton.setAttribute(
                    'aria-expanded',
                    'true'
                );

                setTimeout(function () {

                    if (searchInput) {
                        searchInput.focus();
                    }

                }, 150);

            }


            /*
            |--------------------------------------------------------------------------
            | Close Search
            |--------------------------------------------------------------------------
            */

            function closeSearchPanel() {

                if (!searchPanel) {
                    return;
                }

                searchPanel.classList.remove('show');
                searchOverlay.classList.remove('show');

                searchPanel.setAttribute(
                    'aria-hidden',
                    'true'
                );

                searchOpenButton.setAttribute(
                    'aria-expanded',
                    'false'
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Toggle Search
            |--------------------------------------------------------------------------
            */

            if (searchOpenButton) {

                searchOpenButton.addEventListener(
                    'click',
                    function (event) {

                        event.stopPropagation();

                        if (
                            searchPanel.classList.contains('show')
                        ) {
                            closeSearchPanel();
                        } else {
                            openSearchPanel();
                        }

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Close Button
            |--------------------------------------------------------------------------
            */

            if (searchCloseButton) {

                searchCloseButton.addEventListener(
                    'click',
                    closeSearchPanel
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Overlay Click
            |--------------------------------------------------------------------------
            */

            if (searchOverlay) {

                searchOverlay.addEventListener(
                    'click',
                    closeSearchPanel
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Escape Key
            |--------------------------------------------------------------------------
            */

            document.addEventListener(
                'keydown',
                function (event) {

                    if (
                        event.key === 'Escape' &&
                        searchPanel.classList.contains('show')
                    ) {
                        closeSearchPanel();
                        searchOpenButton.focus();
                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Sticky Navbar
            |--------------------------------------------------------------------------
            */

            function updateStickyNavbar() {

                if (!navbar || !header) {
                    return;
                }

                if (window.scrollY > 140) {

                    if (
                        !navbar.classList.contains('is-sticky')
                    ) {
                        header.style.minHeight =
                            navbar.offsetHeight + 'px';

                        navbar.classList.add('is-sticky');
                    }

                } else {

                    navbar.classList.remove('is-sticky');
                    header.style.minHeight = '';

                }

            }

            window.addEventListener(
                'scroll',
                updateStickyNavbar,
                { passive: true }
            );

            updateStickyNavbar();


            /*
            |--------------------------------------------------------------------------
            | Close Mobile Menu After Clicking Link
            |--------------------------------------------------------------------------
            */

            if (navbarMenu) {

                const menuLinks =
                    navbarMenu.querySelectorAll('.nav-link');

                menuLinks.forEach(function (menuLink) {

                    menuLink.addEventListener(
                        'click',
                        function () {

                            if (
                                window.innerWidth < 992 &&
                                window.bootstrap
                            ) {
                                const collapseInstance =
                                    bootstrap.Collapse.getOrCreateInstance(
                                        navbarMenu
                                    );

                                collapseInstance.hide();
                            }

                        }
                    );

                });

            }


            /*
            |--------------------------------------------------------------------------
            | Close Search On Window Resize
            |--------------------------------------------------------------------------
            */

            window.addEventListener(
                'resize',
                function () {

                    if (
                        window.innerWidth < 400 &&
                        searchPanel.classList.contains('show')
                    ) {
                        closeSearchPanel();
                    }

                }
            );

        }
    );

</script>
