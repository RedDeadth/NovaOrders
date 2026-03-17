import { PageProps } from '@/types';
import { Head, Link } from '@inertiajs/react';

export default function Welcome({
    auth,
}: PageProps) {
    return (
        <>
            <Head title="NovaOrders - Sistema de Gestión de Pedidos" />
            <div className="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 text-white overflow-hidden">
                {/* Animated background elements */}
                <div className="fixed inset-0 pointer-events-none">
                    <div className="absolute top-[-20%] right-[-10%] w-[600px] h-[600px] rounded-full bg-indigo-500/10 blur-3xl animate-pulse" />
                    <div className="absolute bottom-[-20%] left-[-10%] w-[500px] h-[500px] rounded-full bg-violet-500/10 blur-3xl animate-pulse" style={{ animationDelay: '1s' }} />
                    <div className="absolute top-[40%] left-[30%] w-[300px] h-[300px] rounded-full bg-cyan-500/5 blur-3xl animate-pulse" style={{ animationDelay: '2s' }} />
                </div>

                {/* Navigation */}
                <nav className="relative z-10 border-b border-white/5 backdrop-blur-xl bg-white/[0.02]">
                    <div className="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
                        <div className="flex items-center gap-3">
                            <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                                <svg className="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" strokeWidth={2} stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                </svg>
                            </div>
                            <span className="text-xl font-bold bg-gradient-to-r from-white to-white/70 bg-clip-text text-transparent">
                                NovaOrders
                            </span>
                        </div>

                        <div className="flex items-center gap-3">
                            {auth.user ? (
                                <Link
                                    href={route('dashboard')}
                                    className="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-600 text-white font-medium text-sm hover:shadow-lg hover:shadow-indigo-500/25 transition-all duration-300 hover:scale-105"
                                >
                                    Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href={route('login')}
                                        className="px-5 py-2.5 rounded-xl border border-white/10 text-white/80 font-medium text-sm hover:bg-white/5 hover:border-white/20 transition-all duration-300"
                                    >
                                        Iniciar Sesión
                                    </Link>
                                    <Link
                                        href={route('register')}
                                        className="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-600 text-white font-medium text-sm hover:shadow-lg hover:shadow-indigo-500/25 transition-all duration-300 hover:scale-105"
                                    >
                                        Registrarse
                                    </Link>
                                </>
                            )}
                        </div>
                    </div>
                </nav>

                {/* Hero Section */}
                <section className="relative z-10 max-w-7xl mx-auto px-6 pt-20 pb-16">
                    <div className="text-center max-w-4xl mx-auto">
                        <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-sm font-medium mb-8">
                            <span className="w-2 h-2 rounded-full bg-green-400 animate-pulse" />
                            Sistema de Gestión v1.0
                        </div>

                        <h1 className="text-5xl md:text-7xl font-bold leading-tight mb-6">
                            <span className="bg-gradient-to-r from-white via-white to-white/50 bg-clip-text text-transparent">
                                Gestiona tus
                            </span>
                            <br />
                            <span className="bg-gradient-to-r from-indigo-400 via-violet-400 to-cyan-400 bg-clip-text text-transparent">
                                pedidos y ventas
                            </span>
                        </h1>

                        <p className="text-lg md:text-xl text-white/50 max-w-2xl mx-auto mb-10 leading-relaxed">
                            Plataforma integral para administrar productos, pedidos, clientes y reportes de ventas.
                            Diseñada con arquitectura hexagonal para máximo rendimiento.
                        </p>

                        <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
                            <Link
                                href={route('register')}
                                className="group px-8 py-3.5 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-600 text-white font-semibold text-base hover:shadow-xl hover:shadow-indigo-500/30 transition-all duration-300 hover:scale-105 flex items-center gap-2"
                            >
                                Comenzar Ahora
                                <svg className="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" strokeWidth={2.5} stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </Link>
                            <Link
                                href={route('login')}
                                className="px-8 py-3.5 rounded-xl border border-white/10 text-white/70 font-semibold text-base hover:bg-white/5 hover:border-white/20 transition-all duration-300"
                            >
                                Ya tengo cuenta
                            </Link>
                        </div>
                    </div>
                </section>

                {/* Stats */}
                <section className="relative z-10 max-w-5xl mx-auto px-6 py-12">
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                        {[
                            { label: 'Productos', value: '∞', icon: '📦' },
                            { label: 'Pedidos', value: 'Real-time', icon: '🛒' },
                            { label: 'Clientes', value: 'Ilimitados', icon: '👥' },
                            { label: 'Reportes', value: 'Automáticos', icon: '📊' },
                        ].map((stat, i) => (
                            <div
                                key={i}
                                className="group p-5 rounded-2xl bg-white/[0.03] border border-white/[0.06] hover:bg-white/[0.06] hover:border-white/10 transition-all duration-300 text-center"
                            >
                                <div className="text-2xl mb-2">{stat.icon}</div>
                                <div className="text-lg font-bold text-white/90">{stat.value}</div>
                                <div className="text-sm text-white/40">{stat.label}</div>
                            </div>
                        ))}
                    </div>
                </section>

                {/* Features */}
                <section className="relative z-10 max-w-7xl mx-auto px-6 py-16">
                    <div className="text-center mb-16">
                        <h2 className="text-3xl md:text-4xl font-bold mb-4">
                            <span className="bg-gradient-to-r from-white to-white/60 bg-clip-text text-transparent">
                                Todo lo que necesitas
                            </span>
                        </h2>
                        <p className="text-white/40 text-lg max-w-xl mx-auto">
                            Funcionalidades diseñadas para optimizar tu flujo de trabajo
                        </p>
                    </div>

                    <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {[
                            {
                                title: 'Gestión de Productos',
                                description: 'CRUD completo con categorías, stock, SKU y precios. Control total de tu inventario.',
                                icon: (
                                    <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                ),
                                gradient: 'from-amber-500 to-orange-600',
                            },
                            {
                                title: 'Pedidos Inteligentes',
                                description: 'Máquina de estados automatizada: Pendiente → Pagado → Enviado → Entregado.',
                                icon: (
                                    <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                ),
                                gradient: 'from-indigo-500 to-blue-600',
                            },
                            {
                                title: 'Roles y Permisos',
                                description: 'Sistema de roles: Administrador, Vendedor y Cliente con control de acceso granular.',
                                icon: (
                                    <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                    </svg>
                                ),
                                gradient: 'from-emerald-500 to-teal-600',
                            },
                            {
                                title: 'Reportes de Ventas',
                                description: 'Reportes por día y mes con queries optimizadas. Dashboard con métricas clave.',
                                icon: (
                                    <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                    </svg>
                                ),
                                gradient: 'from-violet-500 to-purple-600',
                            },
                            {
                                title: 'API RESTful',
                                description: '23 endpoints protegidos con Sanctum. Documentación completa y validaciones estrictas.',
                                icon: (
                                    <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
                                    </svg>
                                ),
                                gradient: 'from-cyan-500 to-blue-600',
                            },
                            {
                                title: 'Arquitectura Hexagonal',
                                description: 'DDD ligero con Ports & Adapters. Código limpio, testeable y mantenible.',
                                icon: (
                                    <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M21 7.5l-2.25-1.313M21 7.5v2.25m0-2.25l-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3l2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0 6.75l2.25-1.313M12 21.75V19.5m0 2.25l-2.25-1.313m0-16.875L12 2.25l2.25 1.313M21 14.25v2.25l-2.25 1.313m-13.5 0L3 16.5v-2.25" />
                                    </svg>
                                ),
                                gradient: 'from-rose-500 to-pink-600',
                            },
                        ].map((feature, i) => (
                            <div
                                key={i}
                                className="group p-6 rounded-2xl bg-white/[0.03] border border-white/[0.06] hover:bg-white/[0.06] hover:border-white/10 transition-all duration-500 hover:-translate-y-1"
                            >
                                <div className={`w-12 h-12 rounded-xl bg-gradient-to-br ${feature.gradient} flex items-center justify-center mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300`}>
                                    {feature.icon}
                                </div>
                                <h3 className="text-lg font-semibold text-white mb-2">{feature.title}</h3>
                                <p className="text-white/40 text-sm leading-relaxed">{feature.description}</p>
                            </div>
                        ))}
                    </div>
                </section>

                {/* Tech Stack */}
                <section className="relative z-10 max-w-5xl mx-auto px-6 py-16">
                    <div className="p-8 rounded-3xl bg-gradient-to-br from-white/[0.04] to-white/[0.01] border border-white/[0.08]">
                        <h3 className="text-center text-lg font-semibold text-white/60 mb-8">Construido con</h3>
                        <div className="flex flex-wrap items-center justify-center gap-8">
                            {['Laravel 12', 'React 19', 'TypeScript', 'Inertia.js', 'Tailwind CSS', 'Sanctum', 'SQLite/MySQL'].map((tech, i) => (
                                <div
                                    key={i}
                                    className="px-5 py-2.5 rounded-xl bg-white/[0.04] border border-white/[0.06] text-white/50 text-sm font-medium hover:bg-white/[0.08] hover:text-white/70 transition-all duration-300"
                                >
                                    {tech}
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* CTA */}
                <section className="relative z-10 max-w-4xl mx-auto px-6 py-16 text-center">
                    <h2 className="text-3xl md:text-4xl font-bold mb-4">
                        <span className="bg-gradient-to-r from-indigo-400 to-violet-400 bg-clip-text text-transparent">
                            ¿Listo para empezar?
                        </span>
                    </h2>
                    <p className="text-white/40 text-lg mb-8">
                        Crea tu cuenta y comienza a gestionar tus pedidos en minutos
                    </p>
                    <Link
                        href={route('register')}
                        className="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-600 text-white font-semibold text-base hover:shadow-xl hover:shadow-indigo-500/30 transition-all duration-300 hover:scale-105"
                    >
                        Crear Cuenta Gratis
                        <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" strokeWidth={2.5} stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </Link>
                </section>

                {/* Footer */}
                <footer className="relative z-10 border-t border-white/5 py-8">
                    <div className="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
                        <div className="flex items-center gap-2 text-white/30 text-sm">
                            <div className="w-6 h-6 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center">
                                <svg className="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" strokeWidth={2.5} stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                </svg>
                            </div>
                            NovaOrders © 2026
                        </div>
                        <div className="text-white/20 text-sm">
                            Laravel · React · Arquitectura Hexagonal · DDD
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
}
