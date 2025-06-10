import * as Icons from '@heroicons/react/24/outline';

export const iconGroups: Record<string, { name: string; icon: React.FC<any> }[]> = {
    System: [
        { name: 'Cog6ToothIcon', icon: Icons.Cog6ToothIcon },
        { name: 'AdjustmentsHorizontalIcon', icon: Icons.AdjustmentsHorizontalIcon },
        { name: 'BoltIcon', icon: Icons.BoltIcon },
    ],
    Users: [
        { name: 'UserIcon', icon: Icons.UserIcon },
        { name: 'UserGroupIcon', icon: Icons.UserGroupIcon },
        { name: 'IdentificationIcon', icon: Icons.IdentificationIcon },
        { name: 'UserPlusIcon', icon: Icons.UserPlusIcon },
        { name: 'UserMinusIcon', icon: Icons.UserMinusIcon },
        { name: 'UsersIcon', icon: Icons.UsersIcon },
        { name: 'UserCircleIcon', icon: Icons.UserCircleIcon },
    ],
    Objects: [
        { name: 'BuildingOfficeIcon', icon: Icons.BuildingOfficeIcon },
        { name: 'BriefcaseIcon', icon: Icons.BriefcaseIcon },
        { name: 'CubeIcon', icon: Icons.CubeIcon },
        { name: 'ArchiveBoxIcon', icon: Icons.ArchiveBoxIcon },
    ],
    Documents: [
        { name: 'DocumentTextIcon', icon: Icons.DocumentTextIcon },
        { name: 'FolderIcon', icon: Icons.FolderIcon },
        { name: 'ClipboardDocumentIcon', icon: Icons.ClipboardDocumentIcon },
    ],
    Misc: Object.entries(Icons)
        .filter(([name]) => name.endsWith('Icon'))
        .filter(([name]) =>
            !['Cog6ToothIcon','UserIcon','UserGroupIcon','BuildingOfficeIcon','DocumentTextIcon'].includes(name)
        )
        .map(([name, icon]) => ({ name, icon })),
};
