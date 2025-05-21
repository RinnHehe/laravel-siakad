import HeaderTitle from '@/Components/HeaderTitle';
import AppLayout from '@/Layouts/AppLayout';
import { IconLayout2 } from '@tabler/icons-react';

export default function Dashboard(props) {
    return (
        <div className="flex w-full flex-col pb-32">
            <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                <HeaderTitle
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconLayout2}
                />
            </div>
        </div>
    );
}

Dashboard.layout = (page) => <AppLayout title={page.props.page_settings.title} children={page} />;
