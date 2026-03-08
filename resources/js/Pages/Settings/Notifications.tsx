import { Head, router } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import SettingsLayout from '@/Layouts/SettingsLayout';
import { useState } from 'react';

/* ── Toggle ── */
function Toggle({ checked, onChange }: { checked: boolean; onChange: (v: boolean) => void }) {
    return (
        <button
            type="button"
            role="switch"
            aria-checked={checked}
            onClick={() => onChange(!checked)}
            className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200
                focus:outline-none focus:ring-2 focus:ring-avaa-primary focus:ring-offset-2 flex-shrink-0 cursor-pointer
                ${checked ? 'bg-avaa-primary' : 'bg-gray-200'}`}
        >
            <span className={`inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform duration-200
                ${checked ? 'translate-x-6' : 'translate-x-1'}`} />
        </button>
    );
}

/* ── Card ── */
function Card({ children }: { children: React.ReactNode }) {
    return <div className="bg-white rounded-2xl border border-gray-200 overflow-hidden">{children}</div>;
}

/* ── Toggle row ── */
function NotifRow({ label, description, checked, onChange }: {
    label: string; description: string; checked: boolean; onChange: (v: boolean) => void;
}) {
    return (
        <div className="flex items-start justify-between gap-4 px-6 py-4 border-b border-gray-50 last:border-b-0">
            <div className="min-w-0">
                <p className="text-sm font-medium text-avaa-dark">{label}</p>
                <p className="text-xs text-avaa-muted mt-0.5">{description}</p>
            </div>
            <Toggle checked={checked} onChange={onChange} />
        </div>
    );
}

/* ── Types ── */
type NotifGroup = {
    new_job_matches: boolean;
    application_status: boolean;
    interview_invites: boolean;
    messages_from_employers: boolean;
};

type NotificationSettings = {
    email: NotifGroup;
    in_app: NotifGroup;
};

interface Props {
    settings: NotificationSettings;
}

const NOTIF_ITEMS = [
    {
        key: 'new_job_matches' as keyof NotifGroup,
        label: 'New Job Matches',
        description: 'Get notified when a job matches your specific skills and experience.',
    },
    {
        key: 'application_status' as keyof NotifGroup,
        label: 'Application Status Updates',
        description: 'Stay updated on your application progress from submission to hire.',
    },
    {
        key: 'interview_invites' as keyof NotifGroup,
        label: 'Interview Invites',
        description: 'Receive instant alerts for scheduled interviews and meeting links.',
    },
    {
        key: 'messages_from_employers' as keyof NotifGroup,
        label: 'Messages from Employers',
        description: 'Get an email when a potential employer sends you a direct message.',
    },
];

const DEFAULT_GROUP: NotifGroup = {
    new_job_matches: true,
    application_status: true,
    interview_invites: true,
    messages_from_employers: true,
};

export default function Notifications({ settings }: Props) {
    const [form, setForm] = useState<NotificationSettings>({
        email: { ...DEFAULT_GROUP, ...(settings?.email ?? {}) },
        in_app: { ...DEFAULT_GROUP, ...(settings?.in_app ?? {}) },
    });

    const [saving, setSaving] = useState(false);
    const [saved, setSaved] = useState(false);

    const update = (channel: 'email' | 'in_app', key: keyof NotifGroup, value: boolean) => {
        const next: NotificationSettings = {
            ...form,
            [channel]: { ...form[channel], [key]: value },
        };
        setForm(next);
        setSaving(true);
        router.patch(route('settings.notifications.update'), next, {
            preserveScroll: true,
            onSuccess: () => { setSaving(false); setSaved(true); setTimeout(() => setSaved(false), 2000); },
            onError: () => setSaving(false),
        });
    };

    return (
        <>
            <Head title="Notifications" />
            <AppLayout pageTitle="Settings" pageSubtitle="Manage your preferences" activeNav="Profile">
                <SettingsLayout title="Notification Preferences" subtitle="Choose how and when you want to be notified about job matches, messages, and platform updates.">

                    {/* ── Email Notifications ── */}
                    <Card>
                        <div className="px-6 py-4 border-b border-gray-100">
                            <h3 className="text-sm font-semibold text-avaa-dark">Email Notifications</h3>
                        </div>
                        {NOTIF_ITEMS.map(item => (
                            <NotifRow
                                key={`email-${item.key}`}
                                label={item.label}
                                description={item.description}
                                checked={form.email[item.key]}
                                onChange={v => update('email', item.key, v)}
                            />
                        ))}
                    </Card>

                    {/* ── In-App Notifications ── */}
                    <Card>
                        <div className="px-6 py-4 border-b border-gray-100">
                            <h3 className="text-sm font-semibold text-avaa-dark">In-App Notifications</h3>
                        </div>
                        {NOTIF_ITEMS.map(item => (
                            <NotifRow
                                key={`inapp-${item.key}`}
                                label={item.label}
                                description={item.description}
                                checked={form.in_app[item.key]}
                                onChange={v => update('in_app', item.key, v)}
                            />
                        ))}
                    </Card>

                    {/* Auto-save indicator */}
                    <div className="flex justify-end h-6">
                        {saving && <span className="text-xs text-avaa-muted">Saving…</span>}
                        {saved && <span className="text-xs text-avaa-teal font-medium">✓ Preferences saved</span>}
                    </div>

                </SettingsLayout>
            </AppLayout>
        </>
    );
}