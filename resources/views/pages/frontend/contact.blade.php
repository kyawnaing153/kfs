@extends('layouts.frontend.app')

@section('title', 'Contact Us - KFS Scaffolding')

@section('content')
<div id="page-contact" class="page active">
    <div class="pt-20 lg:pt-24 min-h-screen bg-navy-800 blueprint-grid-dark">
        
        {{-- Header --}}
        <div class="bg-navy-800 blueprint-grid-dark py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-orange-500/15 border border-orange-500/25 rounded-full mb-4">
                    <i data-lucide="mail" class="w-3.5 h-3.5 text-orange-400"></i>
                    <span class="text-xs font-semibold text-orange-400 uppercase tracking-wider">Get in Touch</span>
                </div>
                <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4">
                    Contact <span class="text-orange-400">Our Team</span>
                </h1>
                <p class="text-lg text-steel-300 max-w-2xl mx-auto">
                    Have a project in mind? Our experts are ready to help you find the right scaffolding solution.
                </p>
            </div>
        </div>

        {{-- Contact Form Section --}}
        <section class="py-16 bg-navy-800 blueprint-grid-dark">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-5 gap-12">
                    
                    {{-- Contact Form --}}
                    <div class="lg:col-span-3">
                        <h2 class="font-display text-2xl font-bold text-white mb-6">Send us a Message</h2>
                        
                        @if(session('success'))
                            <div class="mb-6 flex items-center gap-3 px-4 py-3 bg-green-500/10 border border-green-500/20 rounded-xl text-sm text-green-400">
                                <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif
                        
                        <form action="#" method="POST" id="contactForm" class="space-y-5">
                            @csrf
                            
                            <div class="grid sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="form-label">Full Name <span class="req">*</span></label>
                                    <input type="text" name="name" required 
                                        class="form-input @error('name') border-red-500 @enderror" 
                                        placeholder="John Smith"
                                        value="{{ old('name') }}">
                                    @error('name')
                                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="form-label">Company</label>
                                    <input type="text" name="company" 
                                        class="form-input" 
                                        placeholder="Your company name"
                                        value="{{ old('company') }}">
                                </div>
                            </div>
                            
                            <div>
                                <label class="form-label">Email <span class="req">*</span></label>
                                <input type="email" name="email" required 
                                    class="form-input @error('email') border-red-500 @enderror" 
                                    placeholder="john@company.com"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="form-label">Phone</label>
                                <input type="tel" name="phone" 
                                    class="form-input" 
                                    placeholder="+1 (555) 000-0000"
                                    value="{{ old('phone') }}">
                            </div>
                            
                            <div>
                                <label class="form-label">Service Needed</label>
                                <select name="service_type" class="form-input appearance-none cursor-pointer">
                                    <option value="">Select a service...</option>
                                    <option value="rental" {{ old('service_type') == 'rental' ? 'selected' : '' }}>Equipment Rental</option>
                                    <option value="purchase" {{ old('service_type') == 'purchase' ? 'selected' : '' }}>Equipment Purchase</option>
                                    <option value="assessment" {{ old('service_type') == 'assessment' ? 'selected' : '' }}>Site Assessment</option>
                                    <option value="consulting" {{ old('service_type') == 'consulting' ? 'selected' : '' }}>Safety Consulting</option>
                                    <option value="other" {{ old('service_type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="form-label">Message <span class="req">*</span></label>
                                <textarea name="message" required rows="4" 
                                    class="form-input resize-none @error('message') border-red-500 @enderror" 
                                    placeholder="Tell us about your project requirements...">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <button type="submit" 
                                class="inline-flex items-center gap-2 px-8 py-4 bg-orange-500 text-white font-semibold rounded-xl hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/25">
                                <i data-lucide="send" class="w-4 h-4"></i>
                                Send Message
                            </button>
                        </form>
                    </div>
                    
                    {{-- Contact Info Sidebar --}}
                    <div class="lg:col-span-2 space-y-5">
                        <h2 class="font-display text-2xl font-bold text-white mb-6">Contact Info</h2>
                        
                        {{-- Phone --}}
                        <div class="flex items-start gap-4 p-5 bg-navy-700/30 border border-white/10 rounded-xl hover:bg-navy-700/50 transition-colors">
                            <div class="w-12 h-12 bg-orange-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i data-lucide="phone" class="w-5 h-5 text-orange-400"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-white mb-1">Phone</h4>
                                <p class="text-sm text-steel-400">
                                    <a href="tel:{{ $settings['phone'] ?? '' }}">{{ $settings['phone'] ?? '' }}</a>
                                </p>
                                <p class="text-xs text-steel-500 mt-1">Mon–Sat, 7AM–5PM</p>
                            </div>
                        </div>
                        
                        {{-- Email --}}
                        <div class="flex items-start gap-4 p-5 bg-navy-700/30 border border-white/10 rounded-xl hover:bg-navy-700/50 transition-colors">
                            <div class="w-12 h-12 bg-orange-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i data-lucide="mail" class="w-5 h-5 text-orange-400"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-white mb-1">Email</h4>
                                <p class="text-sm text-steel-400">
                                    <a href="mailto:{{ $settings['email'] ?? '' }}">{{ $settings['email'] ?? '' }}</a>
                                </p>
                                <p class="text-xs text-steel-500 mt-1">Response within hours</p>
                            </div>
                        </div>
                        
                        {{-- Location --}}
                        <div class="flex items-start gap-4 p-5 bg-navy-700/30 border border-white/10 rounded-xl hover:bg-navy-700/50 transition-colors">
                            <div class="w-12 h-12 bg-orange-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i data-lucide="map-pin" class="w-5 h-5 text-orange-400"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-white mb-1">Location</h4>
                                <p class="text-sm text-steel-400">{{ $settings['address'] ?? '' }}</p>
                            </div>
                        </div>
                        
                        {{-- Map Placeholder --}}
                        <div class="rounded-xl overflow-hidden border border-white/10 aspect-video bg-navy-700/30 flex items-center justify-center">
                            <div class="text-center">
                                <i data-lucide="map" class="w-10 h-10 text-steel-600 mx-auto mb-2"></i>
                                <p class="text-sm text-steel-500">Interactive Map</p>
                                <p class="text-xs text-steel-600 mt-1">Coming soon</p>
                            </div>
                        </div>
                        
                        {{-- Social Links --}}
                        <div class="flex items-center gap-3 pt-2">
                            <a href="#" class="w-10 h-10 bg-navy-700/30 border border-white/10 rounded-lg flex items-center justify-center hover:bg-orange-500/20 hover:border-orange-500/30 transition-colors">
                                <i data-lucide="facebook" class="w-4 h-4 text-steel-400"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-navy-700/30 border border-white/10 rounded-lg flex items-center justify-center hover:bg-orange-500/20 hover:border-orange-500/30 transition-colors">
                                <i data-lucide="twitter" class="w-4 h-4 text-steel-400"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-navy-700/30 border border-white/10 rounded-lg flex items-center justify-center hover:bg-orange-500/20 hover:border-orange-500/30 transition-colors">
                                <i data-lucide="linkedin" class="w-4 h-4 text-steel-400"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-navy-700/30 border border-white/10 rounded-lg flex items-center justify-center hover:bg-orange-500/20 hover:border-orange-500/30 transition-colors">
                                <i data-lucide="instagram" class="w-4 h-4 text-steel-400"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection