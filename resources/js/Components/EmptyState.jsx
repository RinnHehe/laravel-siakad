export default function EmptyState({
    title = 'Tidak ada data',
    subtitle = 'Mulailah dengan membuat data baru',
    icon: Icon,
  }) {
    return (
      <div className="flex flex-col items-center p-4 border border-dashed border-secondary rounded-lg text-center">
        <Icon className="text-blue-600 size-12" />
        <h3 className="mt-2 text-lg font-semibold text-foreground">{title}</h3>
        <p className="mt-1 text-sm text-muted-foreground">{subtitle}</p>
      </div>
    );
  }
  