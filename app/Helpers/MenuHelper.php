<?php

namespace App\Helpers;

class MenuHelper
{
    public static function getMainActivity()
    {
        return [
            [
                'icon' => 'dashboard',
                'name' => 'Dashboard',
                'path' => '/admin/dashboard',
            ],
            [
                'icon' => 'staff',
                'name' => 'Staff',
                'path' => '/admin/staffs',
            ],
            [
                'icon' => 'users',
                'name' => 'Users',
                'path' => '/admin/users',
            ],
            [
                'icon' => 'customer',
                'name' => 'Customer',
                'path' => '/admin/customers',
            ],
            [
                'icon' => 'supplier',
                'name' => 'Supplier',
                'path' => '/admin/suppliers',
            ],
        ];
    }

    public static function getRentSalePurchaseSections(){
        return [
            [
                'icon' => 'rent',
                'name' => 'Rents',
                'basePath' => '/admin/rents',  // Add base path for matching
                'subItems' => [
                    ['name' => 'Rent', 'path' => '/admin/rents'],
                    ['name' => 'Rent Return', 'path' => '/admin/rent-returns'],
                    ['name' => 'Rent Payment', 'path' => '/admin/rent-payments'],
                ],
            ],
            [
                'icon' => 'sales',
                'name' => 'Sales',
                'basePath' => '/admin/sales',
                'path' => '/admin/sales',
            ],
            [
                'icon' => 'expenses',
                'name' => 'Expenses',
                'basePath' => '/admin/expenses',
                'path' => '/admin/expenses',
            ],
            [
                'icon' => 'purchase',
                'name' => 'Purchase',
                'basePath' => '/admin/purchases',
                'path' => '/admin/purchases',
            ]
        ];
    }

    public static function getListSection(){
        return [
            [
                'icon' => 'rental-list',
                'name' => 'Rental List',
                'path' => '/admin/rents/items-list',
            ],
            [
                'icon' => 'returned-list',
                'name' => 'Returned List',
                'path' => '/admin/rent-returns/items-list',
            ],
            [
                'icon' => 'sales-list',
                'name' => 'Sales List',
                'path' => '/admin/sales/items-list',
            ],
        ];
    }

    public static function getProductAndQuotationNav()
    {
        return [
            [
                'icon' => 'products',
                'name' => 'Products',
                'path' => '/admin/products',
            ],
            [
                'icon' => 'quotation',
                'name' => 'Quotation',
                'path' => '/admin/quotation'
            ],
            [
                'icon' => 'custom-invoice',
                'name' => 'Custom Invoice',
                'path' => '/admin/custom-invoice'
            ],
        ];
    }

    public static function getOthersItems()
    {
        return [
            [
                'icon' => 'rent-report',
                'name' => 'Rent Overdue Report',
                'path' => '/admin/rents/overdue-report',
            ],
            [
                'icon' => 'calendar',
                'name' => 'Calendar',
                'path' => '/calendar',
            ],
        ];
    }

    public static function getMenuGroups()
    {
        return [
            [
                'title' => 'Main',
                'items' => self::getMainActivity()
            ],
            [
                'title' => 'Rents & Sales',
                'items' => self::getRentSalePurchaseSections()
            ],
            [
                'title' => 'Lists',
                'items' => self::getListSection()
            ],
            [
                'title' => 'Products & Quotation',
                'items' => self::getProductAndQuotationNav()
            ],
            [
                'title' => 'Others',
                'items' => self::getOthersItems()
            ],
        ];
    }

    public static function isActive($path)
    {
        $currentPath = request()->path();
        $currentFullPath = '/' . $currentPath;
        
        // Exact match
        if ($currentFullPath === $path) {
            return true;
        }
        
        // Check if current path starts with the menu path (for parent routes)
        // This handles /admin/rents, /admin/rents/create, /admin/rents/1/edit, etc.
        if (str_starts_with($currentFullPath, $path) && $path !== '/') {
            return true;
        }
        
        return false;
    }
    
    // New method to check if a submenu should be open based on base path
    public static function isSubmenuActive($basePath)
    {
        $currentPath = request()->path();
        $currentFullPath = '/' . $currentPath;
        
        // Check if current path starts with the base path
        if (str_starts_with($currentFullPath, $basePath)) {
            return true;
        }
        
        return false;
    }

    public static function getIconSvg($iconName)
    {
        $icons = [
            'dashboard' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z" fill="currentColor"/></svg>',

            'staff' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 22V19C5 15.6863 7.68629 13 11 13H13C16.3137 13 19 15.6863 19 19V22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M18 4.5L19.5 6L23 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',

            'users' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17 21V19C17 16.2386 14.7614 14 12 14H5C2.23858 14 0 16.2386 0 19V21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.5 11C10.9853 11 13 8.98528 13 6.5C13 4.01472 10.9853 2 8.5 2C6.01472 2 4 4.01472 4 6.5C4 8.98528 6.01472 11 8.5 11Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 21V19C19.9973 16.8631 18.992 14.8379 17.35 13.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M15 2.5C16.642 3.50355 17.668 5.34683 17.668 7.328C17.668 9.30917 16.642 11.1525 15 12.156" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',

            'customer' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 21V19C20 16.2386 17.7614 14 15 14H9C6.23858 14 4 16.2386 4 19V21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 11L24 8L22 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 11L0 8L2 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',

            'supplier' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 7L12 3L4 7L12 11L20 7Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 7V17L12 21L20 17V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 11V21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 5.5L16 9.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',

            'rent' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 12H22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 8H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M6 4H18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><rect x="3" y="12" width="18" height="8" rx="1" stroke="currentColor" stroke-width="1.5"/><path d="M8 16H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M12 12V20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',

            'sales' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 3V19C3 20.1046 3.89543 21 5 21H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 15L11 11L14 14L21 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="18" cy="13" r="2" stroke="currentColor" stroke-width="1.5"/><circle cx="6" cy="17" r="2" stroke="currentColor" stroke-width="1.5"/></svg>',

            'expenses' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2V4M12 20V22M4 12H2M6.5 6.5L5 5M17.5 6.5L19 5M22 12H20M17.5 17.5L19 19M6.5 17.5L5 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M12 8V12L14 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',

            'purchase' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 7L12 12L22 7L12 2L2 7Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 4.5L15 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',

            'rental-list' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 6H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M8 12H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M8 18H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M3 6H3.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M3 12H3.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M3 18H3.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',

            'returned-list' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 6H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M8 12H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M8 18H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M3 6H3.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M3 12H3.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M3 18H3.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M5 4L3 6L5 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',

            'sales-list' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 6H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M8 12H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M8 18H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M3 6H3.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M3 12H3.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M3 18H3.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M4 3L6 5L4 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',

            'products' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 7L12 12L4 7L12 2L20 7Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 7V12C4 14 7 16 12 16C17 16 20 14 20 12V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 12V17C4 19 7 21 12 21C17 21 20 19 20 17V12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 12V21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',

            'quotation' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 4H20V20H4V4Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M8 8H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M8 12H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M8 16H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M17 16L19 18L17 20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',

            'custom-invoice' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 4H20V20H4V4Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M8 8H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M8 12H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M14 16H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M8 16H10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M16 8L18 10L16 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',

            'rent-report' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 3V19C3 20.1046 3.89543 21 5 21H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 15L10 12L13 15L20 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M17 21V9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M14 21V12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M20 21V8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><rect x="6" y="12" width="3" height="9" rx="0.5" stroke="currentColor" stroke-width="1.5"/></svg>',

            'calendar' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M8 2C8.41421 2 8.75 2.33579 8.75 2.75V3.75H15.25V2.75C15.25 2.33579 15.5858 2 16 2C16.4142 2 16.75 2.33579 16.75 2.75V3.75H18.5C19.7426 3.75 20.75 4.75736 20.75 6V9V19C20.75 20.2426 19.7426 21.25 18.5 21.25H5.5C4.25736 21.25 3.25 20.2426 3.25 19V9V6C3.25 4.75736 4.25736 3.75 5.5 3.75H7.25V2.75C7.25 2.33579 7.58579 2 8 2ZM8 5.25H5.5C5.08579 5.25 4.75 5.58579 4.75 6V8.25H19.25V6C19.25 5.58579 18.9142 5.25 18.5 5.25H16H8ZM19.25 9.75H4.75V19C4.75 19.4142 5.08579 19.75 5.5 19.75H18.5C18.9142 19.75 19.25 19.4142 19.25 19V9.75Z" fill="currentColor"></path></svg>',
        ];

        return $icons[$iconName] ?? '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="currentColor"/></svg>';
    }
}