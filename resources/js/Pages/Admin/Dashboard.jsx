import AppLayout from "@/Layouts/AppLayout";
import { IconLayout2 } from "@tabler/icons-react";
import HeaderTitle from "@/Components/HeaderTitle";

export default function Dashboard(props) {
  return (
    <div className="flex flex-col w-full pb-32">
      <div className="flex flex-col items-start justify-between mb-8 gap-y-4 lg:flex-row lg:items-center">
        <HeaderTitle
          title={props.page_settings.title}
          subtitle={props.page_settings.subtitle}
          icon={IconLayout2}
        />
      </div>
    </div>
  );
}


Dashboard.layout = (page) => <AppLayout title={page.props.page_settings.title}>{page}</AppLayout>
