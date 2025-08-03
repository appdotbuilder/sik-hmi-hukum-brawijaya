import { AppShell } from '@/components/app-shell';
import { AppHeader } from '@/components/app-header';
import { AppSidebar } from '@/components/app-sidebar';
import { AppContent } from '@/components/app-content';
import { Breadcrumbs } from '@/components/breadcrumbs';
import { SidebarInset } from '@/components/ui/sidebar';
import { type BreadcrumbItem } from '@/types';

interface AppLayoutProps {
    children: React.ReactNode;
    breadcrumbs?: BreadcrumbItem[];
    variant?: 'header' | 'sidebar';
}

export default function AppLayout({ children, breadcrumbs, variant = 'sidebar' }: AppLayoutProps) {
    if (variant === 'header') {
        return (
            <AppShell variant="header">
                <AppHeader />
                <AppContent>
                    {breadcrumbs && <Breadcrumbs breadcrumbs={breadcrumbs} />}
                    {children}
                </AppContent>
            </AppShell>
        );
    }

    return (
        <AppShell variant="sidebar">
            <AppSidebar />
            <SidebarInset>
                <AppHeader />
                <AppContent>
                    {breadcrumbs && <Breadcrumbs breadcrumbs={breadcrumbs} />}
                    {children}
                </AppContent>
            </SidebarInset>
        </AppShell>
    );
}