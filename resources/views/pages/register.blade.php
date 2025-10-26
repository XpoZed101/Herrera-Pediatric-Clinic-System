@extends('layouts.site')

@section('content')
<section class="bg-doodles min-h-screen">
    <div class="mx-auto max-w-3xl px-4 py-6 sm:py-8">
        <h1 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">Patient Registration</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Complete the steps below to register your child as a patient.</p>

        <p class="mt-2 text-xs text-gray-500">Step {{ $step }} of 4</p>

        <!-- Background handled by bg-doodles on the section; removed inline image. -->

        {{-- Step content --}}
        @php
        $inputBase = 'mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent dark:border-gray-700 dark:bg-gray-900 dark:text-white';
        $selectBase = 'mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent dark:border-gray-700 dark:bg-gray-900 dark:text-white';
        $textareaBase = 'mt-1 w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent dark:border-gray-700 dark:bg-gray-900 dark:text-white';
        $btnPrimary = 'inline-flex items-center justify-center rounded-lg bg-accent px-5 py-2.5 text-white font-medium shadow-sm transition hover:bg-accent/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent';
        $btnSecondary = 'rounded-lg border border-gray-300 px-4 py-2 text-gray-700 dark:border-gray-700 dark:text-gray-300';
        @endphp

        <div class="mt-6 rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            @if ($step === 1)
            <form method="POST" action="{{ route('register.step1.store') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @csrf
                <div class="md:col-span-2">
                    <h2 class="text-base font-medium text-gray-900 dark:text-white">Child Information</h2>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                    <input name="child_name" type="text" required class="{{ $inputBase }}" value="{{ old('child_name', $data['child']['child_name'] ?? '') }}" />
                    @error('child_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Date of Birth</label>
                    <input name="date_of_birth" type="date" required class="{{ $inputBase }}" value="{{ old('date_of_birth', $data['child']['date_of_birth'] ?? '') }}" />
                    @error('date_of_birth')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Sex</label>
                    <select name="sex" required class="{{ $selectBase }}">
                        <option value="male" @selected(($data['child']['sex'] ?? '' )==='male' )>Male</option>
                        <option value="female" @selected(($data['child']['sex'] ?? '' )==='female' )>Female</option>
                    </select>
                    @error('sex')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <h2 class="mt-2 text-base font-medium text-gray-900 dark:text-white">Parent/Guardian</h2>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input name="guardian_name" type="text" class="{{ $inputBase }}" value="{{ old('guardian_name', $data['guardian']['name'] ?? '') }}" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Phone</label>
                    <input name="guardian_phone" type="tel" inputmode="numeric" pattern="\d{11}" maxlength="11" class="{{ $inputBase }}" value="{{ old('guardian_phone', $data['guardian']['phone'] ?? '') }}" placeholder="11-digit number" />
                    @error('guardian_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input name="guardian_email" type="email" class="{{ $inputBase }}" value="{{ old('guardian_email', $data['guardian']['email'] ?? '') }}" />
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Account Password</label>
                    <input name="password" type="password" required class="{{ $inputBase }}" placeholder="Enter a password to create account" />
                    @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <input type="hidden" name="role" value="patient" />

                <div class="md:col-span-2">
                    <h2 class="mt-2 text-base font-medium text-gray-900 dark:text-white">Emergency Contact</h2>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input name="emergency_name" type="text" class="{{ $inputBase }}" value="{{ old('emergency_name', $data['emergency']['name'] ?? '') }}" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Phone</label>
                    <input name="emergency_phone" type="tel" inputmode="numeric" pattern="\d{11}" maxlength="11" class="{{ $inputBase }}" value="{{ old('emergency_phone', $data['emergency']['phone'] ?? '') }}" placeholder="11-digit number" />
                    @error('emergency_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2"></div>
                <div class="md:col-span-2 flex justify-end mt-2">
                    <button type="submit" class="{{ $btnPrimary }}">Continue</button>
                </div>
            </form>
            @elseif ($step === 2)
            <form method="POST" action="{{ route('register.step2.store') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @csrf
                <div class="md:col-span-2">
                    <h2 class="text-base font-medium text-gray-900 dark:text-white">Medical History</h2>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Medications (one per line)</label>
                    <textarea name="medications" rows="4" class="{{ $textareaBase }}">{{ old('medications', $data['medical']['medications'] ?? '') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Allergies (one per line)</label>
                    <textarea name="allergies" rows="4" class="{{ $textareaBase }}">{{ old('allergies', $data['medical']['allergies'] ?? '') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Past Medical Problems</label>
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        @php $conds = ['asthma','ear_infections','eczema','seizures','heart_problems','adhd','autism','diabetes','developmental_delays','other']; @endphp
                        @foreach ($conds as $c)
                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                            <input type="checkbox" name="past_conditions[]" value="{{ $c }}" @checked(in_array($c, $data['medical']['past_conditions'] ?? [])) />
                            <span>{{ str_replace('_',' ', ucfirst($c)) }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Immunizations</label>
                    <div class="mt-2 flex gap-4 text-sm">
                        @foreach (['yes'=>'Up to date','no'=>'Not up to date','not_sure'=>'Not sure'] as $val => $text)
                        <label class="flex items-center gap-2">
                            <input type="radio" name="immunizations_status" value="{{ $val }}" @checked(($data['medical']['immunizations_status'] ?? '' )===$val) />
                            <span class="text-gray-700 dark:text-gray-300">{{ $text }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="md:col-span-2 flex justify-between">
                    <a href="{{ route('register.step.show', ['step' => 1]) }}" class="{{ $btnSecondary }}">Back</a>
                    <button type="submit" class="{{ $btnPrimary }}">Continue</button>
                </div>
            </form>
            @elseif ($step === 3)
            <form method="POST" action="{{ route('register.step3.store') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @csrf
                <div class="md:col-span-2">
                    <h2 class="text-base font-medium text-gray-900 dark:text-white">Development</h2>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Areas of Concern</label>
                    @php $areas = ['speech_language','walking_movement','learning','behavior','social_skills','no_concerns']; @endphp
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        @foreach ($areas as $a)
                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                            <input type="checkbox" name="concerns[]" value="{{ $a }}" @checked(in_array($a, $data['development']['concerns'] ?? [])) />
                            <span>{{ str_replace('_',' ', ucfirst($a)) }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Notes</label>
                    <textarea name="notes" rows="4" class="{{ $textareaBase }}">{{ old('notes', $data['development']['notes'] ?? '') }}</textarea>
                </div>
                <div class="md:col-span-2 flex justify-between">
                    <a href="{{ route('register.step.show', ['step' => 2]) }}" class="{{ $btnSecondary }}">Back</a>
                    <button type="submit" class="{{ $btnPrimary }}">Continue</button>
                </div>
            </form>
            @elseif ($step === 4)
            <form method="POST" action="{{ route('register.step4.store') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @csrf
                <div class="md:col-span-2">
                    <h2 class="text-base font-medium text-gray-900 dark:text-white">Current Symptoms</h2>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Symptoms</label>
                    @php $symptoms = ['fever','cough','rash','ear_pain','stomach_pain','diarrhea','vomiting','headaches','trouble_breathing','other']; @endphp
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        @foreach ($symptoms as $s)
                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                            <input type="checkbox" name="symptom_types[]" value="{{ $s }}" @checked(in_array($s, $data['symptoms']['types'] ?? [])) />
                            <span>{{ str_replace('_',' ', ucfirst($s)) }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Details (optional)</label>
                    <textarea name="symptom_details" rows="4" class="{{ $textareaBase }}">{{ old('symptom_details', $data['symptoms']['details'] ?? '') }}</textarea>
                </div>
                <div class="md:col-span-2 flex justify-between">
                    <a href="{{ route('register.step.show', ['step' => 3]) }}" class="{{ $btnSecondary }}">Back</a>
                    <button type="submit" class="{{ $btnPrimary }}">Submit Registration</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</section>

{{-- Data bridge for external JS (separation of concerns) --}}
<div id="register-data"
    data-errors="{{ e(json_encode($errors->all())) }}"
    data-success="{{ e(json_encode(session('success'))) }}"
    data-error="{{ e(json_encode(session('error'))) }}"
    data-step="{{ $step }}"></div>
@endsection
