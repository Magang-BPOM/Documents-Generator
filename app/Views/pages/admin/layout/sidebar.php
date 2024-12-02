<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>

        #menu-container a {
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        #menu-container a:hover {
            background-color: rgba(59, 130, 246, 0.1);
            transform: translateX(5px); 
        }

        #menu-container ul a {
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        #menu-container ul a:hover {
            background-color: rgba(59, 130, 246, 0.2);
            transform: translateX(5px);
        }

        .rotate-180 {
            transform: rotate(180deg);
            transition: transform 0.3s ease;
        }
    </style>
</head>


<body>

    <div id="hs-application-sidebar" class="hs-overlay [--auto-close:lg] hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform w-[260px] h-full hidden fixed inset-y-0 start-0 z-[60] bg-white border-e border-gray-200 lg:block lg:translate-x-0 lg:end-auto lg:bottom-0 dark:bg-neutral-800 dark:border-neutral-700" role="dialog" tabindex="-1" aria-label="Sidebar">
        <div class="relative flex flex-col h-full max-h-full">
            <div class="px-6 pt-4 mb-4">
                <!-- Logo -->
                <a class="flex-none rounded-xl text-xl inline-block font-semibold focus:outline-none focus:opacity-80" href="#" aria-label="Preline">
                    <img class="w-40 h-auto dark:hidden" src="https://1.bp.blogspot.com/-YCWJVRc-y0M/Wg0U4GYmw2I/AAAAAAAAE80/Q0V4U7CE3_IsfFkLBRLlatIxMj_cmknXgCLcBGAs/s1600/Badan%2BPOM.png" alt="Dummy Logo Black">
                    <img class="w-40 h-auto hidden dark:block" src="https://1.bp.blogspot.com/-YCWJVRc-y0M/Wg0U4GYmw2I/AAAAAAAAE80/Q0V4U7CE3_IsfFkLBRLlatIxMj_cmknXgCLcBGAs/s1600/Badan%2BPOM.png" alt="Dummy Logo White">
                </a>
                <!-- End Logo -->
            </div>

            <div class="h-full overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
                <ul class="space-y-2 px-4" id="menu-container">

                </ul>
            </div>

            <div class="px-4 py-2">
                <form method="POST" action="<?= base_url('logout') ?>" class="flex items-center">
                    <button type="submit" class="flex items-center py-2.5 px-4 text-red-600 rounded-lg hover:bg-red-100 focus:outline-none">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="ml-2">Logout</span>
                    </button>
                </form>
            </div>

        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", () => {
            fetch('/menu.json')
                .then(response => response.json())
                .then(data => {
                    const sidebarMenu = document.querySelector('#menu-container');

                    data.forEach(menuItem => {
                        let listItem = document.createElement('li');

                        let link = document.createElement('a');
                        link.href = menuItem.url;
                        link.className = 'flex items-center py-3 px-3 text-gray-800 rounded-lg dark:hover:bg-gray-700 hover:bg-gray-200 focus:outline-none  dark:text-white';
                        link.innerHTML = `${menuItem.icon} <span class="ml-2">${menuItem.title}</span>`;

                        if (menuItem.subMenu && menuItem.subMenu.length > 0) {
                            let toggleButton = document.createElement('button');
                            toggleButton.className = 'ml-auto';
                            toggleButton.innerHTML = '<i class="fas fa-chevron-down"></i>';
                            link.appendChild(toggleButton);

                            let subMenuList = document.createElement('ul');
                            subMenuList.className = 'ml-4 space-y-1 hidden';

                            menuItem.subMenu.forEach(subItem => {
                                let subListItem = document.createElement('li');

                                let subLink = document.createElement('a');
                                subLink.href = subItem.url;
                                subLink.className = 'flex items-center py-1 px-2 text-gray-600 rounded-lg dark:hover:bg-gray-600  focus:outline-none  dark:text-white';
                                subLink.innerHTML = `${subItem.icon}<span class="m-2">${subItem.title}</span>`;

                                subListItem.appendChild(subLink);
                                subMenuList.appendChild(subListItem);
                            });

                            listItem.appendChild(link);
                            listItem.appendChild(subMenuList);

                            link.addEventListener('click', (event) => {
                                event.preventDefault();
                                subMenuList.classList.toggle('hidden');
                                toggleButton.querySelector('i').classList.toggle('rotate-180');
                            });
                        } else {
                            listItem.appendChild(link);
                        }

                        sidebarMenu.appendChild(listItem);


                    });
                })
                .catch(error => console.error('Error loading menu:', error));
                
        });

        document.addEventListener("DOMContentLoaded", () => {
            const toggleSidebarButton = document.getElementById("toggle-sidebar");
            const sidebar = document.getElementById("hs-application-sidebar");
            const sidebarOverlay = document.getElementById("sidebar-overlay");

            const showSidebar = () => {
                sidebar.classList.remove("hidden-sidebar");
                sidebar.classList.add("visible-sidebar");
                sidebarOverlay.style.display = "block";
            };

            const hideSidebar = () => {
                sidebar.classList.remove("visible-sidebar");
                sidebar.classList.add("hidden-sidebar");
                sidebarOverlay.style.display = "none";
            };

            toggleSidebarButton.addEventListener("click", () => {
                const isSidebarVisible = sidebar.classList.contains("visible-sidebar");
                if (isSidebarVisible) {
                    hideSidebar();
                } else {
                    showSidebar();
                }
            });

            sidebarOverlay.addEventListener("click", hideSidebar);
        });
    </script>
</body>