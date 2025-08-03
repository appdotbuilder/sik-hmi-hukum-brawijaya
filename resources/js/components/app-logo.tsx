

export default function AppLogo() {
    return (
        <>
            <div className="flex aspect-square size-8 items-center justify-center rounded-md bg-[#065B2C] text-white">
                <span className="text-lg">📚</span>
            </div>
            <div className="ml-1 grid flex-1 text-left text-sm">
                <span className="mb-0.5 truncate leading-tight font-semibold">SIK HMI</span>
                <span className="truncate text-xs text-sidebar-foreground/60">Hukum Brawijaya</span>
            </div>
        </>
    );
}
