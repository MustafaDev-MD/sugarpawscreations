@extends('frontend.layouts.app')

@section('title', "Contact Us")

@section('content')

<section id="content-section" class="page page-contact">

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="content-wrap min-height-600">
                    <div class="row margin-bottom-40">
                        <div class="col-lg-4">

                            <address>
                                <i class="fas fa-envelope"></i> <a href="mailto:clumsycutie20@gmail.com">clumsycutie20@gmail.com</a>
                            </address>

                            <div class="social-icons">
                                <ul>
                                    <li><a target="_blank" href="https://www.facebook.com" title="Follow us on Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a target="_blank" href="https://twitter.com/" title="Follow us on Twitter"><i class="fab fa-twitter"></i></a></li>
                                    <li><a target="_blank" href="https://www.discord.com" title="Follow us on Discord"><i class="fab fa-discord"></i></a></li>
                                </ul>
                            </div>

                        </div>
                        <div class="col-md-8">

                            <h4>Let's connect.</h4>
                            <p>Let's bring your ideas to life with custom designs that tell your story. Share your vision below!.</p>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">

                            <form id="contact-form" action="{{ route('contact.submit') }}" method="POST">
                                @csrf

                                <input type="text" name="website" style="position:absolute; left:-9999px; opacity:0;" tabindex="-1" autocomplete="off">
                                <input type="hidden" name="form_time" value="{{ time() }}">
                                <!-- <input type="hidden" name="recaptcha_token" id="recaptcha_token"> -->

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="full_name" placeholder="Enter Your Full Name" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <input type="email" class="form-control" name="email" placeholder="Enter Your Email Address" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="discord_username" placeholder="Enter Your Discord Username" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="social_link" placeholder="Any Other Social Link (Optional)">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <textarea class="form-control" name="commission_details" rows="8" placeholder="Enter Your Commission Details (Optional)"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row captcha">
                                    <div class="col-lg-12">
                                        <div class="g-recaptcha"
                                            data-sitekey="{{ config('services.recaptcha.site_key') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <!-- <button type="submit" id="submit-btn" class="btn btn-primary btn-rounded-5x btn-block">Send Message</button> -->
                                        <button
                                            type="submit"
                                            id="submit-btn"
                                            class="btn btn-primary btn-rounded-5x btn-block">
                                            Send Message
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div id="toast-container" style="position:fixed; bottom:20px; right:20px; z-index:9999;"></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script> -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script>
    (function() {
        const form = document.getElementById('contact-form');
        const submitBtn = document.getElementById('submit-btn');
        const container = document.getElementById('toast-container');

        function showToast(type, msg) {
            const div = document.createElement('div');
            div.className = 'toast-msg toast-' + type;
            div.textContent = msg;
            const remove = () => {
                div.classList.add('toast-removing');
                setTimeout(() => div.remove(), 200);
            };
            div.addEventListener('click', remove);
            container.appendChild(div);
            setTimeout(remove, 5000);
        }

        function clearInvalid() {
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        }

        function markInvalid(name) {
            const el = form.querySelector('[name="' + name + '"]');
            if (el) el.classList.add('is-invalid');
        }

        form.addEventListener('submit', async function(e) {

    e.preventDefault();

    clearInvalid();

    const originalText = submitBtn.textContent;

    submitBtn.disabled = true;
    submitBtn.textContent = 'Sending...';

    try {

        const res = await fetch(form.action, {

            method: 'POST',

            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },

            body: new FormData(form)

        });

        const data = await res.json();

        if (res.ok) {

            showToast('success', data.message);

            form.reset();

            grecaptcha.reset();

            return;
        }

        if (res.status === 422 && data.errors) {

            const firstField = Object.keys(data.errors)[0];

            markInvalid(firstField);

            showToast('error', data.errors[firstField][0]);

            return;
        }

        showToast('error', data.message ?? 'Something went wrong');

    } catch (err) {

        console.error(err);

        showToast('error', 'Network Error');

    } finally {

        submitBtn.disabled = false;

        submitBtn.textContent = originalText;

        const timeInput = form.querySelector('input[name="form_time"]');

        if (timeInput) {
            timeInput.value = Math.floor(Date.now() / 1000);
        }

    }

});

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            grecaptcha.execute();
        });

        // async function onSubmit(token) {
        window.onSubmit = async function(token) {
            const submitBtn = document.getElementById('submit-btn');

            submitBtn.disabled = true;

            const originalText = submitBtn.textContent;

            submitBtn.textContent = 'Sending...';

            try {

                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: new FormData(form)
                });

                const data = await res.json();

                if (res.ok) {
                    showToast('success', data.message);
                    form.reset();
                    grecaptcha.reset();
                    return;
                }

                if (res.status === 422 && data.errors) {

                    const firstField = Object.keys(data.errors)[0];

                    markInvalid(firstField);

                    showToast('error', data.errors[firstField][0]);

                    grecaptcha.reset();

                    return;
                }

                showToast('error', data.message ?? 'Something went wrong');
                grecaptcha.reset();

            } catch (e) {

                showToast('error', 'Network Error');
                grecaptcha.reset();

            } finally {

                submitBtn.disabled = false;
                submitBtn.textContent = originalText;

            }

        }
    })();
</script>

@endsection