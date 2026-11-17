
<x-app-layout>
    <x-slot name="header">
        <div class="tap-page-heading">
            <div>
                <div class="tap-eyebrow">
                    <span class="tap-eyebrow-dot"></span>
                    CARD MANAGEMENT
                </div>
                <h2>Edit Business Card</h2>
                <p>Update your professional identity and customise your digital card.</p>
            </div>

            <a href="{{ route('business-cards.index', ['workspace' => $workspace->id]) }}"
               class="tap-back-link">
                <span>←</span> Back to Cards
            </a>
        </div>
    </x-slot>

    @php
        $themeData = $themes->map(fn ($theme) => [
            'id' => (string) $theme->id,
            'name' => $theme->name,
            'colors' => $theme->colors ?? [],
            'settings' => $theme->settings ?? [],
        ])->values();

        $initialData = [
            'card_theme_id' => (string) old('card_theme_id', $businessCard->card_theme_id ?? ''),
            'first_name' => old('first_name', $businessCard->first_name),
            'last_name' => old('last_name', $businessCard->last_name),
            'job_title' => old('job_title', $businessCard->job_title),
            'company_name' => old('company_name', $businessCard->company_name),
            'bio' => old('bio', $businessCard->bio),
            'email' => old('email', $businessCard->email),
            'phone' => old('phone', $businessCard->phone),
            'website' => old('website', $businessCard->website),
            'show_email' => (bool) old('show_email', $businessCard->show_email),
            'show_phone' => (bool) old('show_phone', $businessCard->show_phone),
            'profile_photo_url' => \App\Support\StorageUrl::url($businessCard->profile_photo),
            'cover_photo_url' => \App\Support\StorageUrl::url($businessCard->cover_photo),
        ];
    @endphp

    <div class="tap-edit-page"
         x-data="tapCardBuilder(@js($themeData), @js($initialData))">

        <div class="tap-edit-container">

            {{-- INTRODUCTION --}}
            <div class="tap-edit-intro">
                <div>
                    <span class="tap-section-label">YOUR DIGITAL IDENTITY</span>
                    <h1>Make your card <span>stand out.</span></h1>
                    <p>
                        Keep your professional details up to date.
                        Your changes will be reflected on your digital business card.
                    </p>
                </div>

                <div class="tap-edit-status">
                    <span class="tap-status-dot"></span>
                    {{ ucfirst($businessCard->status) }}
                </div>
            </div>

            {{-- VALIDATION ERRORS --}}
            @if ($errors->any())
                <div class="tap-alert tap-alert-error">
                    <div class="tap-alert-icon">!</div>
                    <div>
                        <strong>Please review the following information.</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('business-cards.update', [
                    'workspace' => $workspace->id,
                    'businessCard' => $businessCard->id
                ]) }}"
                enctype="multipart/form-data"
                class="tap-edit-form"
            >
                @csrf
                @method('PUT')

                <div class="tap-editor-layout">

                    {{-- LEFT: EDITING FORM --}}
                    <div class="tap-editor-fields">

                        {{-- PERSONAL INFORMATION --}}
                        <section class="tap-form-section">
                            <div class="tap-section-heading">
                                <div class="tap-section-icon cyan">01</div>
                                <div>
                                    <h3>Personal Information</h3>
                                    <p>Tell people who you are.</p>
                                </div>
                            </div>

                            <div class="tap-form-grid">
                                <div class="tap-field">
                                    <label for="first_name">First Name <span>*</span></label>
                                    <input
                                        id="first_name"
                                        type="text"
                                        name="first_name"
                                        x-model="firstName"
                                        value="{{ old('first_name', $businessCard->first_name) }}"
                                        required
                                        maxlength="100"
                                        placeholder="Enter your first name"
                                    >
                                    @error('first_name')
                                        <small class="tap-field-error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="tap-field">
                                    <label for="last_name">Last Name</label>
                                    <input
                                        id="last_name"
                                        type="text"
                                        name="last_name"
                                        x-model="lastName"
                                        value="{{ old('last_name', $businessCard->last_name) }}"
                                        maxlength="100"
                                        placeholder="Enter your last name"
                                    >
                                    @error('last_name')
                                        <small class="tap-field-error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="tap-field">
                                    <label for="job_title">Job Title</label>
                                    <input
                                        id="job_title"
                                        type="text"
                                        name="job_title"
                                        x-model="jobTitle"
                                        value="{{ old('job_title', $businessCard->job_title) }}"
                                        placeholder="e.g. Software Developer"
                                    >
                                    @error('job_title')
                                        <small class="tap-field-error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="tap-field">
                                    <label for="company_name">Company / Organisation</label>
                                    <input
                                        id="company_name"
                                        type="text"
                                        name="company_name"
                                        x-model="company"
                                        value="{{ old('company_name', $businessCard->company_name) }}"
                                        placeholder="Your organisation"
                                    >
                                    @error('company_name')
                                        <small class="tap-field-error">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="tap-field tap-field-full">
                                <label for="bio">Professional Biography</label>
                                <textarea
                                    id="bio"
                                    name="bio"
                                    x-model="bio"
                                    rows="4"
                                    maxlength="2000"
                                    placeholder="Write a short professional introduction..."
                                >{{ old('bio', $businessCard->bio) }}</textarea>
                                <div class="tap-field-hint">
                                    Briefly describe what you do and what you can help people with.
                                </div>
                                @error('bio')
                                    <small class="tap-field-error">{{ $message }}</small>
                                @enderror
                            </div>
                        </section>

                        {{-- CONTACT INFORMATION --}}
                        <section class="tap-form-section">
                            <div class="tap-section-heading">
                                <div class="tap-section-icon lime">02</div>
                                <div>
                                    <h3>Contact Information</h3>
                                    <p>Give people a way to connect with you.</p>
                                </div>
                            </div>

                            <div class="tap-form-grid">
                                <div class="tap-field">
                                    <label for="email">Email Address</label>
                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        x-model="email"
                                        value="{{ old('email', $businessCard->email) }}"
                                        placeholder="name@example.com"
                                    >
                                    @error('email')
                                        <small class="tap-field-error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="tap-field">
                                    <label for="phone">Phone Number</label>
                                    <input
                                        id="phone"
                                        type="text"
                                        name="phone"
                                        x-model="phone"
                                        value="{{ old('phone', $businessCard->phone) }}"
                                        placeholder="+260 97X XXX XXX"
                                    >
                                    @error('phone')
                                        <small class="tap-field-error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="tap-field">
                                    <label for="whatsapp">WhatsApp Number</label>
                                    <input
                                        id="whatsapp"
                                        type="text"
                                        name="whatsapp"
                                        value="{{ old('whatsapp', $businessCard->whatsapp) }}"
                                        placeholder="+260 97X XXX XXX"
                                    >
                                    @error('whatsapp')
                                        <small class="tap-field-error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="tap-field">
                                    <label for="website">Website</label>
                                    <input
                                        id="website"
                                        type="url"
                                        name="website"
                                        x-model="website"
                                        value="{{ old('website', $businessCard->website) }}"
                                        placeholder="https://example.com"
                                    >
                                    @error('website')
                                        <small class="tap-field-error">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </section>

                        {{-- LOCATION --}}
                        <section class="tap-form-section">
                            <div class="tap-section-heading">
                                <div class="tap-section-icon orange">03</div>
                                <div>
                                    <h3>Location</h3>
                                    <p>Where people can find you or your business.</p>
                                </div>
                            </div>

                            <div class="tap-field tap-field-full">
                                <label for="address">Physical Address</label>
                                <input
                                    id="address"
                                    type="text"
                                    name="address"
                                    value="{{ old('address', $businessCard->address) }}"
                                    placeholder="Street address or business location"
                                >
                                @error('address')
                                    <small class="tap-field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="tap-form-grid tap-location-grid">
                                <div class="tap-field">
                                    <label for="city">City</label>
                                    <input
                                        id="city"
                                        type="text"
                                        name="city"
                                        value="{{ old('city', $businessCard->city) }}"
                                        placeholder="e.g. Lusaka"
                                    >
                                    @error('city')
                                        <small class="tap-field-error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="tap-field">
                                    <label for="country">Country</label>
                                    <input
                                        id="country"
                                        type="text"
                                        name="country"
                                        value="{{ old('country', $businessCard->country) }}"
                                        placeholder="e.g. Zambia"
                                    >
                                    @error('country')
                                        <small class="tap-field-error">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </section>

                        {{-- CARD APPEARANCE --}}
                        <section class="tap-form-section">
                            <div class="tap-section-heading">
                                <div class="tap-section-icon yellow">04</div>
                                <div>
                                    <h3>Card Appearance</h3>
                                    <p>Choose a look that represents your identity.</p>
                                </div>
                            </div>

                            <div class="tap-field tap-field-full">
                                <label for="card_theme_id">Card Theme</label>
                                <select
                                    id="card_theme_id"
                                    name="card_theme_id"
                                    x-model="themeId"
                                >
                                    <option value="">Default Theme</option>
                                    @foreach ($themes as $theme)
                                        <option value="{{ $theme->id }}">
                                            {{ $theme->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('card_theme_id')
                                    <small class="tap-field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="tap-upload-grid">
                                <div class="tap-upload-card">
                                    <div class="tap-upload-top">
                                        <div class="tap-upload-icon">✦</div>
                                        <div>
                                            <h4>Profile Photo</h4>
                                            <p>Your professional portrait</p>
                                        </div>
                                    </div>

                                    <div class="tap-current-image profile-image">
                                        <template x-if="profilePreview">
                                            <img :src="profilePreview" alt="Profile preview">
                                        </template>
                                        <template x-if="!profilePreview">
                                            <div class="tap-image-placeholder">PHOTO</div>
                                        </template>
                                    </div>

                                    <label for="profile_photo" class="tap-file-label">
                                        Choose new photo
                                    </label>
                                    <input
                                        id="profile_photo"
                                        type="file"
                                        name="profile_photo"
                                        accept=".jpg,.jpeg,.png,.webp"
                                        @change="readImage($event, 'profile')"
                                    >
                                    <small>JPG, PNG or WebP. Maximum 5 MB.</small>
                                    @error('profile_photo')
                                        <small class="tap-field-error">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="tap-upload-card">
                                    <div class="tap-upload-top">
                                        <div class="tap-upload-icon orange-icon">▰</div>
                                        <div>
                                            <h4>Cover Photo</h4>
                                            <p>Your card's banner image</p>
                                        </div>
                                    </div>

                                    <div class="tap-current-image cover-image">
                                        <template x-if="coverPreview">
                                            <img :src="coverPreview" alt="Cover preview">
                                        </template>
                                        <template x-if="!coverPreview">
                                            <div class="tap-image-placeholder">COVER IMAGE</div>
                                        </template>
                                    </div>

                                    <label for="cover_photo" class="tap-file-label">
                                        Choose new cover
                                    </label>
                                    <input
                                        id="cover_photo"
                                        type="file"
                                        name="cover_photo"
                                        accept=".jpg,.jpeg,.png,.webp"
                                        @change="readImage($event, 'cover')"
                                    >
                                    <small>JPG, PNG or WebP. Maximum 8 MB.</small>
                                    @error('cover_photo')
                                        <small class="tap-field-error">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </section>

                        {{-- PRIVACY SETTINGS --}}
                        <section class="tap-form-section">
                            <div class="tap-section-heading">
                                <div class="tap-section-icon cyan">05</div>
                                <div>
                                    <h3>Privacy and Visibility</h3>
                                    <p>Control what visitors can see on your card.</p>
                                </div>
                            </div>

                            <div class="tap-field tap-field-full">
                                <label for="visibility">Card Visibility</label>
                                <select id="visibility" name="visibility" required>
                                    <option value="public"
                                        @selected(old('visibility', $businessCard->visibility) === 'public')>
                                        Public — Anyone with the link can view
                                    </option>
                                    <option value="private"
                                        @selected(old('visibility', $businessCard->visibility) === 'private')>
                                        Private — Not publicly accessible
                                    </option>
                                </select>
                                @error('visibility')
                                    <small class="tap-field-error">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="tap-privacy-options">
                                <label class="tap-toggle-row">
                                    <div class="tap-toggle-info">
                                        <div class="tap-toggle-icon">✉</div>
                                        <div>
                                            <strong>Display email address</strong>
                                            <span>Allow visitors to see your email.</span>
                                        </div>
                                    </div>

                                    <input type="hidden" name="show_email" value="0">
                                    <input
                                        type="checkbox"
                                        name="show_email"
                                        value="1"
                                        x-model="showEmail"
                                    >
                                    <span class="tap-toggle"></span>
                                </label>

                                <label class="tap-toggle-row">
                                    <div class="tap-toggle-info">
                                        <div class="tap-toggle-icon lime-toggle">☎</div>
                                        <div>
                                            <strong>Display phone number</strong>
                                            <span>Allow visitors to see your phone number.</span>
                                        </div>
                                    </div>

                                    <input type="hidden" name="show_phone" value="0">
                                    <input
                                        type="checkbox"
                                        name="show_phone"
                                        value="1"
                                        x-model="showPhone"
                                    >
                                    <span class="tap-toggle"></span>
                                </label>
                            </div>
                        </section>

                        {{-- FORM ACTIONS --}}
                        <div class="tap-form-actions">
                            <a
                                href="{{ route('business-cards.index', ['workspace' => $workspace->id]) }}"
                                class="tap-btn-secondary"
                            >
                                Cancel
                            </a>

                            <button type="submit" class="tap-btn-primary">
                                <span>Save Changes</span>
                                <span class="tap-btn-arrow">↗</span>
                            </button>
                        </div>
                    </div>

                    {{-- RIGHT: LIVE PREVIEW --}}
                    <aside class="tap-editor-preview">
                        <div class="tap-preview-sticky">
                            <div class="tap-preview-heading">
                                <div>
                                    <span class="tap-section-label">LIVE PREVIEW</span>
                                    <h3>Your digital card</h3>
                                </div>
                                <span class="tap-live-indicator">
                                    <span></span> LIVE
                                </span>
                            </div>

                            <x-business-card-preview />

                            <div class="tap-preview-note">
                                <span>✦</span>
                                <p>
                                    Your card preview updates as you edit your details.
                                    Save your changes when you're happy with the result.
                                </p>
                            </div>

                            <div class="tap-preview-tip">
                                <div class="tap-tip-icon">↗</div>
                                <div>
                                    <strong>Make a great first impression.</strong>
                                    <p>Use a clear profile photo and concise professional biography.</p>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </form>
        </div>
    </div>

    <style>
        :root {
            --tap-dark: #151419;
            --tap-dark-soft: #1d1c22;
            --tap-panel: #222128;
            --tap-panel-hover: #292831;
            --tap-border: rgba(255,255,255,.09);
            --tap-white: #f7f7f5;
            --tap-muted: #96949e;
            --tap-cyan: #08D5DB;
            --tap-lime: #B5FF58;
            --tap-orange: #FF5B19;
            --tap-yellow: #FFEB00;
            --tap-display: 'Outfit', sans-serif;
            --tap-body: 'DM Sans', sans-serif;
        }

        .tap-edit-page {
            min-height: 100vh;
            padding: 38px 24px 80px;
            background: var(--tap-dark);
            color: var(--tap-white);
            font-family: var(--tap-body);
        }

        .tap-edit-container {
            width: 100%;
            max-width: 1500px;
            margin: 0 auto;
        }

        .tap-page-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .tap-page-heading h2 {
            margin: 5px 0;
            color: var(--tap-white);
            font-family: var(--tap-display);
            font-size: 23px;
            font-weight: 700;
            letter-spacing: -.7px;
        }

        .tap-page-heading p {
            margin: 0;
            color: var(--tap-muted);
            font-size: 13px;
        }

        .tap-back-link {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            color: var(--tap-muted);
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: color .2s;
        }

        .tap-back-link:hover {
            color: var(--tap-cyan);
        }

        .tap-back-link span {
            font-size: 20px;
        }

        .tap-eyebrow,
        .tap-section-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--tap-cyan);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.8px;
        }

        .tap-eyebrow-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--tap-cyan);
            box-shadow: 0 0 12px rgba(8,213,219,.65);
        }

        .tap-edit-intro {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 30px;
        }

        .tap-edit-intro h1 {
            margin: 10px 0 8px;
            color: var(--tap-white);
            font-family: var(--tap-display);
            font-size: clamp(30px, 4vw, 43px);
            font-weight: 700;
            letter-spacing: -1.8px;
            line-height: 1.1;
        }

        .tap-edit-intro h1 span {
            color: var(--tap-cyan);
        }

        .tap-edit-intro p {
            max-width: 590px;
            margin: 0;
            color: var(--tap-muted);
            font-size: 14px;
            line-height: 1.7;
        }

        .tap-edit-status {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 10px 14px;
            border: 1px solid var(--tap-border);
            border-radius: 30px;
            background: var(--tap-panel);
            color: var(--tap-white);
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
            text-transform: capitalize;
        }

        .tap-status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--tap-lime);
            box-shadow: 0 0 10px rgba(181,255,88,.6);
        }

        .tap-alert {
            display: flex;
            gap: 14px;
            margin-bottom: 25px;
            padding: 18px;
            border: 1px solid rgba(255,91,25,.25);
            border-radius: 13px;
            background: rgba(255,91,25,.07);
        }

        .tap-alert-icon {
            display: flex;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            width: 27px;
            height: 27px;
            border-radius: 50%;
            background: var(--tap-orange);
            color: white;
            font-weight: 800;
        }

        .tap-alert strong {
            color: var(--tap-white);
            font-size: 13px;
        }

        .tap-alert ul {
            margin: 8px 0 0;
            padding-left: 18px;
            color: #ffad8c;
            font-size: 12px;
            line-height: 1.8;
        }

        .tap-editor-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.55fr) minmax(310px, .8fr);
            align-items: start;
            gap: 25px;
        }

        .tap-editor-fields {
            display: flex;
            flex-direction: column;
            gap: 19px;
            min-width: 0;
        }

        .tap-form-section {
            padding: 25px;
            border: 1px solid var(--tap-border);
            border-radius: 16px;
            background: var(--tap-dark-soft);
            box-shadow: 0 12px 35px rgba(0,0,0,.12);
        }

        .tap-section-heading {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 24px;
        }

        .tap-section-icon {
            display: flex;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 11px;
            font-family: var(--tap-display);
            font-size: 12px;
            font-weight: 800;
        }

        .tap-section-icon.cyan {
            background: rgba(8,213,219,.12);
            color: var(--tap-cyan);
        }

        .tap-section-icon.lime {
            background: rgba(181,255,88,.12);
            color: var(--tap-lime);
        }

        .tap-section-icon.orange {
            background: rgba(255,91,25,.12);
            color: var(--tap-orange);
        }

        .tap-section-icon.yellow {
            background: rgba(255,235,0,.1);
            color: var(--tap-yellow);
        }

        .tap-section-heading h3 {
            margin: 0 0 4px;
            color: var(--tap-white);
            font-family: var(--tap-display);
            font-size: 17px;
            font-weight: 650;
            letter-spacing: -.3px;
        }

        .tap-section-heading p {
            margin: 0;
            color: var(--tap-muted);
            font-size: 12px;
        }

        .tap-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 19px;
        }

        .tap-field {
            min-width: 0;
        }

        .tap-field-full {
            margin-top: 20px;
        }

        .tap-field label {
            display: block;
            margin-bottom: 8px;
            color: #d8d7dc;
            font-size: 12px;
            font-weight: 650;
        }

        .tap-field label span {
            color: var(--tap-orange);
        }

        .tap-field input:not([type="file"]),
        .tap-field textarea,
        .tap-field select {
            display: block;
            width: 100%;
            min-height: 46px;
            padding: 12px 14px;
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 9px;
            outline: none;
            background: #18171d;
            color: var(--tap-white);
            font-family: var(--tap-body);
            font-size: 13px;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        .tap-field textarea {
            min-height: 110px;
            resize: vertical;
            line-height: 1.7;
        }

        .tap-field select {
            cursor: pointer;
        }

        .tap-field input::placeholder,
        .tap-field textarea::placeholder {
            color: #65636d;
        }

        .tap-field input:focus,
        .tap-field textarea:focus,
        .tap-field select:focus {
            border-color: var(--tap-cyan);
            background: #1b1a21;
            box-shadow: 0 0 0 3px rgba(8,213,219,.09);
        }

        .tap-field-hint {
            margin-top: 8px;
            color: var(--tap-muted);
            font-size: 11px;
        }

        .tap-field-error {
            display: block;
            margin-top: 7px;
            color: #ff8b68;
            font-size: 11px;
        }

        .tap-location-grid {
            margin-top: 19px;
        }

        .tap-upload-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
            margin-top: 23px;
        }

        .tap-upload-card {
            min-width: 0;
            padding: 17px;
            border: 1px solid var(--tap-border);
            border-radius: 12px;
            background: #19181e;
        }

        .tap-upload-top {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-bottom: 16px;
        }

        .tap-upload-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: rgba(8,213,219,.12);
            color: var(--tap-cyan);
            font-size: 16px;
        }

        .tap-upload-icon.orange-icon {
            background: rgba(255,91,25,.12);
            color: var(--tap-orange);
        }

        .tap-upload-top h4 {
            margin: 0 0 3px;
            color: var(--tap-white);
            font-size: 12px;
            font-weight: 700;
        }

        .tap-upload-top p {
            margin: 0;
            color: var(--tap-muted);
            font-size: 10px;
        }

        .tap-current-image {
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            width: 100%;
            background: #25242c;
            border: 1px dashed rgba(255,255,255,.13);
        }

        .tap-current-image.profile-image {
            width: 100px;
            height: 100px;
            margin: 0 auto 15px;
            border-radius: 50%;
        }

        .tap-current-image.cover-image {
            height: 100px;
            margin-bottom: 15px;
            border-radius: 9px;
        }

        .tap-current-image img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .tap-image-placeholder {
            color: #77747f;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .tap-file-label {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 38px;
            padding: 9px;
            border: 1px solid rgba(8,213,219,.3);
            border-radius: 8px;
            background: rgba(8,213,219,.06);
            color: var(--tap-cyan);
            cursor: pointer;
            font-size: 11px;
            font-weight: 700;
            transition: background .2s;
        }

        .tap-file-label:hover {
            background: rgba(8,213,219,.13);
        }

        .tap-upload-card input[type="file"] {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            opacity: 0;
            pointer-events: none;
        }

        .tap-upload-card > small {
            display: block;
            margin-top: 9px;
            color: var(--tap-muted);
            font-size: 10px;
            line-height: 1.5;
        }

        .tap-privacy-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 21px;
        }

        .tap-toggle-row {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 15px;
            border: 1px solid var(--tap-border);
            border-radius: 11px;
            background: #19181e;
            cursor: pointer;
        }

        .tap-toggle-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .tap-toggle-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 9px;
            background: rgba(8,213,219,.1);
            color: var(--tap-cyan);
        }

        .tap-toggle-icon.lime-toggle {
            background: rgba(181,255,88,.1);
            color: var(--tap-lime);
        }

        .tap-toggle-info strong,
        .tap-toggle-info span {
            display: block;
        }

        .tap-toggle-info strong {
            margin-bottom: 4px;
            color: var(--tap-white);
            font-size: 12px;
            font-weight: 650;
        }

        .tap-toggle-info span {
            color: var(--tap-muted);
            font-size: 10px;
        }

        .tap-toggle-row input {
            position: absolute;
            right: 15px;
            width: 1px;
            height: 1px;
            opacity: 0;
        }

        .tap-toggle {
            position: relative;
            flex-shrink: 0;
            width: 40px;
            height: 22px;
            border-radius: 30px;
            background: #45434d;
            transition: background .2s;
        }

        .tap-toggle::after {
            position: absolute;
            top: 3px;
            left: 3px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: white;
            content: "";
            transition: transform .2s;
        }

        .tap-toggle-row input:checked + .tap-toggle {
            background: var(--tap-cyan);
        }

        .tap-toggle-row input:checked + .tap-toggle::after {
            transform: translateX(18px);
            background: var(--tap-dark);
        }

        .tap-toggle-row input:focus-visible + .tap-toggle {
            outline: 2px solid var(--tap-white);
            outline-offset: 3px;
        }

        .tap-form-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 8px;
        }

        .tap-btn-primary,
        .tap-btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            min-height: 48px;
            padding: 0 22px;
            border-radius: 9px;
            font-family: var(--tap-body);
            font-size: 12px;
            font-weight: 750;
            text-decoration: none;
            cursor: pointer;
            transition: transform .2s, background .2s, border-color .2s;
        }

        .tap-btn-primary {
            border: 1px solid var(--tap-cyan);
            background: var(--tap-cyan);
            color: #101519;
        }

        .tap-btn-primary:hover {
            transform: translateY(-2px);
            background: #35e5e9;
        }

        .tap-btn-arrow {
            font-size: 17px;
        }

        .tap-btn-secondary {
            border: 1px solid var(--tap-border);
            background: var(--tap-panel);
            color: var(--tap-white);
        }

        .tap-btn-secondary:hover {
            border-color: rgba(255,255,255,.25);
            background: var(--tap-panel-hover);
        }

        .tap-editor-preview {
            min-width: 0;
        }

        .tap-preview-sticky {
            position: sticky;
            top: 25px;
            padding: 20px;
            border: 1px solid var(--tap-border);
            border-radius: 16px;
            background: var(--tap-dark-soft);
        }

        .tap-preview-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 20px;
        }

        .tap-preview-heading h3 {
            margin: 6px 0 0;
            color: var(--tap-white);
            font-family: var(--tap-display);
            font-size: 17px;
            font-weight: 650;
        }

        .tap-live-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 9px;
            border: 1px solid rgba(181,255,88,.18);
            border-radius: 20px;
            background: rgba(181,255,88,.06);
            color: var(--tap-lime);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .tap-live-indicator span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--tap-lime);
            box-shadow: 0 0 8px var(--tap-lime);
        }

        .tap-preview-note {
            display: flex;
            gap: 10px;
            margin-top: 18px;
            padding: 13px;
            border: 1px solid rgba(8,213,219,.15);
            border-radius: 10px;
            background: rgba(8,213,219,.045);
        }

        .tap-preview-note > span {
            color: var(--tap-cyan);
        }

        .tap-preview-note p {
            margin: 0;
            color: var(--tap-muted);
            font-size: 11px;
            line-height: 1.7;
        }

        .tap-preview-tip {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-top: 12px;
            padding: 14px 4px 2px;
        }

        .tap-tip-icon {
            display: flex;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 9px;
            background: rgba(181,255,88,.1);
            color: var(--tap-lime);
        }

        .tap-preview-tip strong {
            display: block;
            margin-bottom: 5px;
            color: var(--tap-white);
            font-size: 11px;
        }

        .tap-preview-tip p {
            margin: 0;
            color: var(--tap-muted);
            font-size: 10px;
            line-height: 1.6;
        }

        @media (max-width: 1050px) {
            .tap-editor-layout {
                grid-template-columns: minmax(0, 1fr) 320px;
                gap: 17px;
            }

            .tap-form-section {
                padding: 20px;
            }

            .tap-preview-sticky {
                padding: 16px;
            }
        }

        @media (max-width: 850px) {
            .tap-editor-layout {
                grid-template-columns: 1fr;
            }

            .tap-editor-preview {
                order: -1;
            }

            .tap-preview-sticky {
                position: static;
            }

            .tap-editor-preview .business-card-preview {
                max-width: 480px;
                margin: 0 auto;
            }
        }

        @media (max-width: 600px) {
            .tap-edit-page {
                padding: 25px 14px 55px;
            }

            .tap-page-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .tap-edit-intro {
                align-items: flex-start;
                flex-direction: column;
                gap: 14px;
            }

            .tap-edit-intro h1 {
                font-size: 32px;
            }

            .tap-form-section {
                padding: 18px 15px;
                border-radius: 13px;
            }

            .tap-form-grid,
            .tap-upload-grid {
                grid-template-columns: 1fr;
            }

            .tap-field-full {
                margin-top: 17px;
            }

            .tap-form-actions {
                flex-direction: column-reverse;
            }

            .tap-btn-primary,
            .tap-btn-secondary {
                width: 100%;
            }

            .tap-toggle-row {
                padding: 12px;
            }

            .tap-preview-sticky {
                padding: 15px;
            }
        }
    </style>

    <script>
        function tapCardBuilder(themes, initial = {}) {
            return {
                themes: themes || [],

                themeId: initial.card_theme_id ?? '',
                firstName: initial.first_name ?? '',
                lastName: initial.last_name ?? '',
                jobTitle: initial.job_title ?? '',
                company: initial.company_name ?? '',
                bio: initial.bio ?? '',
                email: initial.email ?? '',
                phone: initial.phone ?? '',
                website: initial.website ?? '',

                showEmail: initial.show_email ?? true,
                showPhone: initial.show_phone ?? true,

                profilePreview: initial.profile_photo_url ?? null,
                coverPreview: initial.cover_photo_url ?? null,

                get selectedTheme() {
                    return this.themes.find(
                        theme => String(theme.id) === String(this.themeId)
                    ) || this.themes.find(
                        theme => theme.name === 'Executive'
                    ) || {
                        name: 'Default Theme',
                        colors: {
                            background: '#F8FAFC',
                            surface: '#FFFFFF',
                            primary: '#2563EB',
                            secondary: '#0F172A',
                            text: '#172554',
                            muted: '#64748B'
                        },
                        settings: {
                            font: 'DM Sans, sans-serif',
                            border_radius: '16px'
                        }
                    };
                },

                get fullName() {
                    return `${this.firstName} ${this.lastName}`.trim() || 'Your Name';
                },

                get initials() {
                    return (
                        (this.firstName.charAt(0) || 'T') +
                        (this.lastName.charAt(0) || 'C')
                    ).toUpperCase();
                },

                readImage(event, type) {
                    const file = event.target.files[0];

                    if (!file) {
                        return;
                    }

                    const allowedTypes = [
                        'image/jpeg',
                        'image/png',
                        'image/webp'
                    ];

                    if (!allowedTypes.includes(file.type)) {
                        alert('Please select a JPG, PNG or WebP image.');
                        event.target.value = '';
                        return;
                    }

                    const maxSize = type === 'profile'
                        ? 5 * 1024 * 1024
                        : 8 * 1024 * 1024;

                    if (file.size > maxSize) {
                        alert(
                            type === 'profile'
                                ? 'Profile photo must not exceed 5 MB.'
                                : 'Cover photo must not exceed 8 MB.'
                        );

                        event.target.value = '';
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = (e) => {
                        if (type === 'profile') {
                            this.profilePreview = e.target.result;
                        } else {
                            this.coverPreview = e.target.result;
                        }
                    };

                    reader.readAsDataURL(file);
                }
            };
        }
    </script>
</x-app-layout>
