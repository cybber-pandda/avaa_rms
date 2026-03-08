import { Head, router } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import SettingsLayout from '@/Layouts/SettingsLayout';
import { useState } from 'react';

/* ── Toggle switch component ── */
function Toggle({ checked, onChange, disabled }: { checked: boolean; onChange: (v: boolean) => void; disabled?: boolean }) {
    return (
        <button
            type="button"
            role="switch"
            aria-checked={checked}
            disabled={disabled}
            onClick={() => onChange(!checked)}
            className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-avaa-primary focus:ring-offset-2 flex-shrink-0
                ${checked ? 'bg-avaa-primary' : 'bg-gray-200'}
                ${disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'}`}
        >
            <span className={`inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform duration-200
                ${checked ? 'translate-x-6' : 'translate-x-1'}`} />
        </button>
    );
}

/* ── Section card ── */
function Card({ children }: { children: React.ReactNode }) {
    return <div className="bg-white rounded-2xl border border-gray-200 overflow-hidden">{children}</div>;
}
function CardHeader({ title, subtitle }: { title: string; subtitle?: string }) {
    return (
        <div className="px-6 py-4 border-b border-gray-100">
            <h3 className="text-sm font-semibold text-avaa-dark">{title}</h3>
            {subtitle && <p className="text-xs text-avaa-muted mt-0.5">{subtitle}</p>}
        </div>
    );
}

/* ── Toggle row ── */
function ToggleRow({ label, description, checked, onChange }: {
    label: string; description: string; checked: boolean; onChange: (v: boolean) => void;
}) {
    return (
        <div className="flex items-start justify-between gap-4 py-4">
            <div className="min-w-0">
                <p className="text-sm font-medium text-avaa-dark">{label}</p>
                <p className="text-xs text-avaa-muted mt-0.5">{description}</p>
            </div>
            <Toggle checked={checked} onChange={onChange} />
        </div>
    );
}

/* ── Visibility option ── */
function VisibilityOption({ value, label, description, selected, onChange }: {
    value: string; label: string; description: string; selected: boolean; onChange: () => void;
}) {
    return (
        <label className={`flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all
            ${selected ? 'border-avaa-primary bg-avaa-primary-light/40' : 'border-gray-100 hover:border-gray-200'}`}>
            <div className={`mt-0.5 w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-all
                ${selected ? 'border-avaa-primary' : 'border-gray-300'}`}>
                {selected && <div className="w-2 h-2 rounded-full bg-avaa-primary" />}
            </div>
            <input type="radio" name="visibility" value={value} checked={selected} onChange={onChange} className="sr-only" />
            <div>
                <p className="text-sm font-semibold text-avaa-dark">{label}</p>
                <p className="text-xs text-avaa-muted mt-0.5">{description}</p>
            </div>
        </label>
    );
}

/* ══════════════════════════════════════════════
   PAGE
══════════════════════════════════════════════ */
interface SecuritySettings {
    two_factor_enabled: boolean;
    login_alert_email: boolean;
    login_alert_push: boolean;
    marketplace_visibility: 'public' | 'agency_only' | 'private';
    show_contact_info: boolean;
    show_ratings: boolean;
    hide_while_employed: boolean;
}

interface Props {
    settings: SecuritySettings;
}

export default function SecurityPrivacy({ settings }: Props) {
    const [form, setForm] = useState<SecuritySettings>({
        two_factor_enabled:     settings?.two_factor_enabled    ?? false,
        login_alert_email:      settings?.login_alert_email     ?? true,
        login_alert_push:       settings?.login_alert_push      ?? true,
        marketplace_visibility: settings?.marketplace_visibility ?? 'public',
        show_contact_info:      settings?.show_contact_info     ?? true,
        show_ratings:           settings?.show_ratings          ?? true,
        hide_while_employed:    settings?.hide_while_employed   ?? false,
    });

    const [saving, setSaving] = useState(false);
    const [saved, setSaved] = useState(false);

    const update = (key: keyof SecuritySettings, value: any) => {
        const next = { ...form, [key]: value };
        setForm(next);
        setSaving(true);
        router.patch(route('settings.security.update'), next, {
            preserveScroll: true,
            onSuccess: () => { setSaving(false); setSaved(true); setTimeout(() => setSaved(false), 2000); },
            onError:   () => setSaving(false),
        });
    };

    return (
        <>
            <Head title="Security & Privacy" />
            <AppLayout pageTitle="Settings" pageSubtitle="Manage your preferences" activeNav="Profile">
                <SettingsLayout title="Security Settings" subtitle="Manage your account security preferences to keep your data safe.">

                    {/* ── 2FA ── */}
                    <Card>
                        <div className="px-6 py-4 flex items-start justify-between gap-4">
                            <div>
                                <h3 className="text-sm font-semibold text-avaa-dark">Two-Factor Authentication (2FA)</h3>
                                <p className="text-xs text-avaa-muted mt-0.5 max-w-md">
                                    Protect your account with an extra layer of security. We will ask for a verification code when you log in on a new device.
                                </p>
                            </div>
                            <Toggle checked={form.two_factor_enabled} onChange={v => update('two_factor_enabled', v)} />
                        </div>
                    </Card>

                    {/* ── Login Alerts ── */}
                    <Card>
                        <CardHeader title="Login Alerts" subtitle="Choose how you want to be notified when a new login is detected on your account." />
                        <div className="px-6 divide-y divide-gray-50">
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-0 divide-y sm:divide-y-0 sm:divide-x divide-gray-50">
                                {/* Email */}
                                <div className="py-4 sm:pr-6">
                                    <div className="flex items-start justify-between gap-4">
                                        <div>
                                            <p className="text-sm font-medium text-avaa-dark">Email Notifications</p>
                                            <p className="text-xs text-avaa-muted mt-0.5">Send an alert your email</p>
                                        </div>
                                        <Toggle checked={form.login_alert_email} onChange={v => update('login_alert_email', v)} />
                                    </div>
                                </div>
                                {/* Push */}
                                <div className="py-4 sm:pl-6">
                                    <div className="flex items-start justify-between gap-4">
                                        <div>
                                            <p className="text-sm font-medium text-avaa-dark">Push Notifications</p>
                                            <p className="text-xs text-avaa-muted mt-0.5">Alerts via desktop or mobile app</p>
                                        </div>
                                        <Toggle checked={form.login_alert_push} onChange={v => update('login_alert_push', v)} />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Card>

                    {/* ── Marketplace Visibility ── */}
                    <Card>
                        <CardHeader title="Marketplace Visibility" />
                        <div className="p-6 space-y-3">
                            <VisibilityOption
                                value="public" label="Public"
                                description="Visible to all potential clients and search engines"
                                selected={form.marketplace_visibility === 'public'}
                                onChange={() => update('marketplace_visibility', 'public')}
                            />
                            <VisibilityOption
                                value="agency_only" label="Agency-only"
                                description="Only verified agencies can view your full profile and ratings"
                                selected={form.marketplace_visibility === 'agency_only'}
                                onChange={() => update('marketplace_visibility', 'agency_only')}
                            />
                            <VisibilityOption
                                value="private" label="Private"
                                description="Your profile is hidden from the marketplace search results"
                                selected={form.marketplace_visibility === 'private'}
                                onChange={() => update('marketplace_visibility', 'private')}
                            />
                        </div>
                    </Card>

                    {/* ── Privacy & Security ── */}
                    <Card>
                        <CardHeader title="Privacy & Security" />
                        <div className="px-6 divide-y divide-gray-50">
                            <ToggleRow
                                label="Show Contact Info"
                                description="Enable clients to reach you directly before booking"
                                checked={form.show_contact_info}
                                onChange={v => update('show_contact_info', v)}
                            />
                            <ToggleRow
                                label="Show Ratings & Reviews"
                                description="Let potential clients see your track record and VA score"
                                checked={form.show_ratings}
                                onChange={v => update('show_ratings', v)}
                            />
                            <ToggleRow
                                label="Hide profile while employed"
                                description="Reduce noise by hiding when you aren't looking for work"
                                checked={form.hide_while_employed}
                                onChange={v => update('hide_while_employed', v)}
                            />
                        </div>

                        {/* Auto-save indicator */}
                        <div className="px-6 py-3 border-t border-gray-50 flex justify-end">
                            {saving && <span className="text-xs text-avaa-muted">Saving…</span>}
                            {saved  && <span className="text-xs text-avaa-teal font-medium">✓ Changes saved</span>}
                        </div>
                    </Card>

                </SettingsLayout>
            </AppLayout>
        </>
    );
}