import { Head, router } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import SettingsLayout from '@/Layouts/SettingsLayout';
import { useState } from 'react';

/* ── Constants — mirrors JobSeeker/Profile.tsx ── */
const JOB_TYPES = ['Full-time', 'Part-time', 'Contract', 'Freelance', 'Internship', 'Remote'];
const INDUSTRIES = ['Information Technology', 'Healthcare', 'Finance', 'Education', 'Manufacturing', 'Retail', 'Marketing', 'Legal', 'Other'];
const CURRENCIES = ['USD', 'PHP', 'EUR', 'GBP', 'SGD', 'AUD'];
const NOTICE_PERIODS = ['Immediately', '1 week', '2 weeks', '1 month', '2 months', '3 months'];
const WORK_STYLES = ['Full-Time', 'Part-Time', 'Flexible'];

/* ── Shared styles ── */
const inputClass = "block w-full rounded-xl border border-gray-200 bg-gray-50 text-gray-900 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-avaa-primary focus:border-transparent transition-all";
const labelClass = "block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5";
const sectionClass = "bg-white rounded-2xl border border-gray-200 overflow-hidden";

/* ── Chip multi-select ── */
function ChipSelect({ options, selected, onChange }: {
    options: string[];
    selected: string[];
    onChange: (v: string[]) => void;
}) {
    const toggle = (v: string) =>
        selected.includes(v)
            ? onChange(selected.filter(x => x !== v))
            : onChange([...selected, v]);

    return (
        <div className="flex flex-wrap gap-2">
            {options.map(o => (
                <button key={o} type="button" onClick={() => toggle(o)}
                    className={`text-sm px-3 py-1.5 rounded-xl border font-medium transition-all
                        ${selected.includes(o)
                            ? 'bg-avaa-primary text-white border-avaa-primary'
                            : 'bg-white text-gray-600 border-gray-200 hover:border-avaa-primary hover:text-avaa-teal'
                        }`}>
                    {o}
                </button>
            ))}
        </div>
    );
}

/* ── Section card ── */
function Card({ children }: { children: React.ReactNode }) {
    return <div className={sectionClass}>{children}</div>;
}
function CardHeader({ title, subtitle }: { title: string; subtitle?: string }) {
    return (
        <div className="px-6 py-4 border-b border-gray-100">
            <h3 className="text-sm font-semibold text-avaa-dark">{title}</h3>
            {subtitle && <p className="text-xs text-avaa-muted mt-0.5">{subtitle}</p>}
        </div>
    );
}

/* ══════════════════════════════════════════════
   TYPES
══════════════════════════════════════════════ */
interface JobPreferences {
    employment_type_preference: string[];
    desired_job_types: string[];
    desired_industries: string[];
    expected_salary_min: string | number;
    expected_salary_max: string | number;
    salary_currency: string;
    willing_to_relocate: boolean;
    // availability fields stored in job_seeker_profiles
    notice_period?: string;
    work_style?: string;
    weekly_hours?: string;
}

interface Props {
    preferences: JobPreferences;
    hasProfile: boolean;
}

/* ══════════════════════════════════════════════
   PAGE
══════════════════════════════════════════════ */
export default function JobPreferences({ preferences, hasProfile }: Props) {
    const [form, setForm] = useState<JobPreferences>({
        employment_type_preference: preferences?.employment_type_preference ?? [],
        desired_job_types: preferences?.desired_job_types ?? [],
        desired_industries: preferences?.desired_industries ?? [],
        expected_salary_min: preferences?.expected_salary_min ?? '',
        expected_salary_max: preferences?.expected_salary_max ?? '',
        salary_currency: preferences?.salary_currency ?? 'USD',
        willing_to_relocate: preferences?.willing_to_relocate ?? false,
        notice_period: preferences?.notice_period ?? '',
        work_style: preferences?.work_style ?? '',
        weekly_hours: preferences?.weekly_hours ?? '',
    });

    const [saving, setSaving] = useState(false);
    const [saved, setSaved] = useState(false);
    const [errors, setErrors] = useState<Record<string, string>>({});

    const save = () => {
        setSaving(true);
        router.patch(route('settings.job-preferences.update'), form as any, {
            preserveScroll: true,
            onSuccess: () => {
                setSaving(false);
                setSaved(true);
                setTimeout(() => setSaved(false), 2500);
            },
            onError: (e) => { setSaving(false); setErrors(e); },
        });
    };

    if (!hasProfile) {
        return (
            <>
                <Head title="Job Preferences" />
                <AppLayout pageTitle="Settings" pageSubtitle="Manage your preferences" activeNav="Settings">
                    <SettingsLayout title="Job Preferences" subtitle="Configure your ideal job criteria.">
                        <Card>
                            <div className="px-6 py-12 text-center">
                                <div className="w-12 h-12 rounded-full bg-avaa-primary-light flex items-center justify-center mx-auto mb-3 text-avaa-teal">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
                                        <rect x="2" y="7" width="20" height="14" rx="2" /><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2" />
                                    </svg>
                                </div>
                                <p className="text-sm font-semibold text-avaa-dark">Complete your profile first</p>
                                <p className="text-xs text-avaa-muted mt-1 mb-4">Job preferences are linked to your VA profile. Set it up to unlock this section.</p>
                                <a href={route('job-seeker.profile.show')}
                                    className="inline-flex items-center gap-2 px-5 py-2 bg-avaa-primary text-white text-sm font-semibold rounded-xl hover:bg-avaa-primary-hover transition-colors">
                                    Go to Profile
                                </a>
                            </div>
                        </Card>
                    </SettingsLayout>
                </AppLayout>
            </>
        );
    }

    return (
        <>
            <Head title="Job Preferences" />
            <AppLayout pageTitle="Settings" pageSubtitle="Manage your preferences" activeNav="Settings">
                <SettingsLayout title="Job Preferences" subtitle="Configure your ideal job type, industries, and availability.">

                    {/* ── Employment Type ── */}
                    <Card>
                        <CardHeader title="Employment Type" subtitle="Select all types of employment you're open to." />
                        <div className="p-6 space-y-5">
                            <div>
                                <label className={labelClass}>Preferred Employment Type</label>
                                <ChipSelect
                                    options={JOB_TYPES}
                                    selected={form.employment_type_preference}
                                    onChange={v => setForm(f => ({ ...f, employment_type_preference: v }))}
                                />
                            </div>
                            <div>
                                <label className={labelClass}>Desired Job Types</label>
                                <ChipSelect
                                    options={JOB_TYPES}
                                    selected={form.desired_job_types}
                                    onChange={v => setForm(f => ({ ...f, desired_job_types: v }))}
                                />
                            </div>
                        </div>
                    </Card>

                    {/* ── Industries ── */}
                    <Card>
                        <CardHeader title="Preferred Industries" subtitle="Which industries are you looking to work in?" />
                        <div className="p-6">
                            <ChipSelect
                                options={INDUSTRIES}
                                selected={form.desired_industries}
                                onChange={v => setForm(f => ({ ...f, desired_industries: v }))}
                            />
                        </div>
                    </Card>

                    {/* ── Salary ── */}
                    <Card>
                        <CardHeader title="Salary Expectations" subtitle="Your expected compensation range." />
                        <div className="p-6 space-y-4">
                            <div>
                                <label className={labelClass}>Currency</label>
                                <select value={form.salary_currency}
                                    onChange={e => setForm(f => ({ ...f, salary_currency: e.target.value }))}
                                    className={`${inputClass} w-28`}>
                                    {CURRENCIES.map(c => <option key={c} value={c}>{c}</option>)}
                                </select>
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className={labelClass}>Minimum</label>
                                    <input type="number" min="0"
                                        value={form.expected_salary_min}
                                        onChange={e => setForm(f => ({ ...f, expected_salary_min: e.target.value }))}
                                        className={inputClass} placeholder="e.g. 50000" />
                                    {errors.expected_salary_min && (
                                        <p className="text-xs text-red-500 mt-1">{errors.expected_salary_min}</p>
                                    )}
                                </div>
                                <div>
                                    <label className={labelClass}>Maximum</label>
                                    <input type="number" min="0"
                                        value={form.expected_salary_max}
                                        onChange={e => setForm(f => ({ ...f, expected_salary_max: e.target.value }))}
                                        className={inputClass} placeholder="e.g. 80000" />
                                    {errors.expected_salary_max && (
                                        <p className="text-xs text-red-500 mt-1">{errors.expected_salary_max}</p>
                                    )}
                                </div>
                            </div>
                        </div>
                    </Card>

                    {/* ── Availability ── */}
                    <Card>
                        <CardHeader title="Availability" subtitle="Let employers know when and how you can work." />
                        <div className="p-6 space-y-4">
                            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label className={labelClass}>Weekly Hours</label>
                                    <input type="text"
                                        value={form.weekly_hours ?? ''}
                                        onChange={e => setForm(f => ({ ...f, weekly_hours: e.target.value }))}
                                        className={inputClass} placeholder="e.g. 40+ Hours" />
                                </div>
                                <div>
                                    <label className={labelClass}>Notice Period</label>
                                    <select value={form.notice_period ?? ''}
                                        onChange={e => setForm(f => ({ ...f, notice_period: e.target.value }))}
                                        className={inputClass}>
                                        <option value="">Select…</option>
                                        {NOTICE_PERIODS.map(p => <option key={p} value={p}>{p}</option>)}
                                    </select>
                                </div>
                                <div>
                                    <label className={labelClass}>Work Style</label>
                                    <select value={form.work_style ?? ''}
                                        onChange={e => setForm(f => ({ ...f, work_style: e.target.value }))}
                                        className={inputClass}>
                                        <option value="">Select…</option>
                                        {WORK_STYLES.map(s => <option key={s} value={s}>{s}</option>)}
                                    </select>
                                </div>
                            </div>

                            {/* Willing to relocate */}
                            <div className="flex items-center justify-between pt-2 border-t border-gray-50">
                                <div>
                                    <p className="text-sm font-medium text-avaa-dark">Willing to Relocate</p>
                                    <p className="text-xs text-avaa-muted mt-0.5">Open to moving for the right opportunity</p>
                                </div>
                                <button
                                    type="button"
                                    role="switch"
                                    aria-checked={form.willing_to_relocate}
                                    onClick={() => setForm(f => ({ ...f, willing_to_relocate: !f.willing_to_relocate }))}
                                    className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200
                                        focus:outline-none focus:ring-2 focus:ring-avaa-primary focus:ring-offset-2 cursor-pointer flex-shrink-0
                                        ${form.willing_to_relocate ? 'bg-avaa-primary' : 'bg-gray-200'}`}>
                                    <span className={`inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform duration-200
                                        ${form.willing_to_relocate ? 'translate-x-6' : 'translate-x-1'}`} />
                                </button>
                            </div>
                        </div>
                    </Card>

                    {/* ── Save bar ── */}
                    <div className="flex items-center justify-end gap-3">
                        {saved && <span className="text-xs text-avaa-teal font-medium">✓ Preferences saved</span>}
                        <button type="button" onClick={save} disabled={saving}
                            className="px-6 py-2.5 bg-avaa-primary hover:bg-avaa-primary-hover text-white text-sm font-semibold rounded-xl transition-colors disabled:opacity-50 flex items-center gap-2">
                            {saving ? (
                                <>
                                    <svg className="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                    </svg>
                                    Saving…
                                </>
                            ) : 'Save Preferences'}
                        </button>
                    </div>

                </SettingsLayout>
            </AppLayout>
        </>
    );
}