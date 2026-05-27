<script src="{{ asset('assets/js/index-section-visibility.js') }}"></script>
<section class="index-section">
    <div class="index-section__header">
        <span class="index-section__title">مشاورین ما</span>
    </div>
    <div class="row g-3" id="consultants-list">
        <!-- Cards will be injected here by JS -->
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        fetch("/api/consultant") 
            .then(res => res.json())
            .then(data => {
                const list = document.getElementById("consultants-list");
                if (!list) return;

                const consultants = Array.isArray(data) ? data : (data.data || []);

                if (!consultants.length) {
                    window.IndexSectionVisibility?.hide(list);
                    return;
                }

                list.innerHTML = "";

                consultants.forEach(consultant => {
                    let card = `
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card shadow-sm text-center border-0" style="border-radius: 15px;">
                                <img src="/files/consultant/${consultant.image}" 
                                     class="card-img-top mx-auto mt-3 rounded-circle" 
                                     alt="${consultant.name}" 
                                     style="width:90px; height:90px; object-fit:cover; border:3px solid #eee;"
                                     onerror="this.src='{{ asset('assets/images/avatar.jpg') }}'">
                                <div class="card-body p-2">
                                    <h6 class="fw-bold mb-1" style="font-size:0.95rem;">${consultant.name}</h6>
                                        <div class="pt-2">
                                            <a href="tel:${consultant.phone_number}" 
                                            class="btn btn-sm rounded-pill"
                                            style="background-color:#E6B31E;"
                                            >
                                            <i class="bi bi-telephone"></i> تماس
                                            </a>
                                            <a href="https://wa.me/${consultant.whatsapp_number}?text=${encodeURIComponent(consultant.whatsapp_default_message)}" 
                                            target="_blank" 
                                            class="btn btn-sm btn-success rounded-pill">
                                            <i class="bi bi-whatsapp"></i> واتساپ
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    list.insertAdjacentHTML("beforeend", card);
                });
            })
            .catch(err => {
                console.error("Error fetching consultants:", err);
                window.IndexSectionVisibility?.hide(document.getElementById("consultants-list"));
            });
    });
</script>
