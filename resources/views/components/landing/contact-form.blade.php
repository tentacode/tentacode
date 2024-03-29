<section class="page-section background-light text-dark mb-0 pb-5" id="contact">
    <div class="container">
        <h2 class="page-section-heading text-center text-uppercase mb-0">Contact</h2>

        <div class="divider-custom">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon">
                <i class="far fa-paper-plane"></i>
            </div>
            <div class="divider-custom-line"></div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                {{-- <div class="alert alert-primary" role="alert">
                    <h4 class="alert-heading">I'm not looking for any job opportunities right now!</h4>
                    <p class="mt-1 mb-1">Thanks for you interest! That said, do leave me a message if you want
                        to contact me for whatever other reason!</p>
                </div> --}}

                @if(Session::has('success')) 
                <div class="alert alert-success" role="alert">
                    {{ Session::get('success') }} 

                    @php
                        Session::forget('success'); 
                    @endphp 
                </div>
                @endif

                <form action="{{ route('contact') }}#contact" method="POST" id="contactForm" name="sentMessage">
                    @csrf
                    @honeypot
                    <div class="form-floating mb-0 pb-2">
                        <input type="text" id="contact_senderName" name="contact[senderName]"
                            required="required" class="form-control" placeholder="Your Name" />
                        <label for="contact_senderName">Votre nom</label>
                    </div>
                    <div class="form-floating mb-0 pb-2">
                        <input type="email" id="contact_senderEmail" name="contact[senderEmail]"
                            required="required" class="form-control" placeholder="Your Email" />
                            <label for="contact_senderEmail">Votre e-mail</label>
                    </div>
                    <div class="form-floating mb-0 pb-2">
                        <textarea type="text" id="contact_message" name="contact[message]" required="required" class="form-control"
                            placeholder="Your Message" style="height: 20rem"></textarea>
                            <label for="contact_message">Votre message</label>
                    </div>
                    <br>
                    <div class="form-group">
                        <button class="btn btn-primary btn-xl shadow-sm" id="sendMessageButton"
                            type="submit">Envoyer</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>