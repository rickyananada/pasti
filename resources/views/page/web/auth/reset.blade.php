<x-auth-layout title="Login / Register">
    <div class="w-lg-550px bg-white rounded shadow-sm p-10 p-lg-15 mx-auto">
        <!--begin::Form-->
        <form class="form w-100" novalidate="novalidate" id="kt_new_password_form">
            <!--begin::Heading-->
            <div class="text-center mb-10">
                <!--begin::Title-->
                <h1 class="text-dark mb-3">Masukkan Password baru</h1>
                <!--end::Title-->
                <!--begin::Link-->
                {{-- <div class="text-gray-400 fw-bold fs-4">Sudah mengatur ulang kata sandi Anda ?
                <a href="authentication/flows/basic/sign-up.html" class="link-primary fw-bolder">Sign in here</a></div> --}}
                <!--end::Link-->
            </div>
            <!--begin::Heading-->
            <!--begin::Input group-->
            <input class="form-control form-control-lg form-control-solid" type="hidden" placeholder="" id="token" autocomplete="off" value="{{$token}}" />
            <div class="mb-10 fv-row" data-kt-password-meter="true">
                <!--begin::Wrapper-->
                <div class="mb-1">
                    <!--begin::Label-->
                    <label class="form-label fw-bolder text-dark fs-6">Password</label>
                    <!--end::Label-->
                    <!--begin::Input wrapper-->
                    <div class="position-relative mb-3">
                        <input class="form-control form-control-lg form-control-solid" type="password" placeholder="" id="password" name="password" autocomplete="off" />
                        <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                            <i class="bi bi-eye-slash fs-2"></i>
                            <i class="bi bi-eye fs-2 d-none"></i>
                        </span>
                    </div>
                    <!--end::Input wrapper-->
                    <!--begin::Meter-->
                    <!--end::Meter-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Hint-->
                <div class="text-muted">Masukkan 8 - 13 character</div>
                <!--end::Hint-->
            </div>
            <!--end::Input group=-->
            <!--begin::Input group=-->
            <div class="fv-row mb-10">
                <label class="form-label fw-bolder text-dark fs-6">Confirm Password</label>
                <input class="form-control form-control-lg form-control-solid" type="password" placeholder="" id="pc" name="password_confirmation" autocomplete="off" />
            </div>
            <!--end::Input group=-->
            <!--begin::Input group=-->
            <!--end::Input group=-->
            <!--begin::Action-->
            <div class="text-center">
                <button type="button" id="kt_new_password_submit" class="btn btn-lg btn-primary fw-bolder">
                    <span class="indicator-label">Submit</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
            <!--end::Action-->
        </form>
        <!--end::Form-->
    </div>
    @section('custom_js')
    <script type="text/javascript">
        "use strict";
        var KTPasswordResetNewPassword = (function() {
            var e,
                t,
                r,
                o,
                s = function() {
                    return 100 === o.getScore();
                };
            return {
                init: function() {
                    (e = document.querySelector("#kt_new_password_form")),
                    (t = document.querySelector("#kt_new_password_submit")),
                    (o = KTPasswordMeter.getInstance(e.querySelector('[data-kt-password-meter="true"]'))),
                    (r = FormValidation.formValidation(e, {
                        fields: {
                            password: {
                                validators: {
                                    notEmpty: {
                                        message: "The password is required"
                                    },
                                    callback: {
                                        message: "Please enter valid password",
                                        callback: function(e) {
                                            // if (e.value.length > 0) return s();
                                        },
                                    },
                                },
                            },
                            "password_confirmation": {
                                validators: {
                                    notEmpty: {
                                        message: "The password confirmation is required"
                                    },
                                    identical: {
                                        compare: function() {
                                            return e.querySelector('[name="password"]').value;
                                        },
                                        message: "The password and its confirm are not the same",
                                    },
                                },
                            },
                            toc: {
                                validators: {
                                    notEmpty: {
                                        message: "You must accept the terms and conditions"
                                    }
                                }
                            },
                        },
                        plugins: {
                            trigger: new FormValidation.plugins.Trigger({
                                event: {
                                    password: !1
                                }
                            }),
                            bootstrap: new FormValidation.plugins.Bootstrap5({
                                rowSelector: ".fv-row",
                                eleInvalidClass: "",
                                eleValidClass: ""
                            })
                        },
                    })),
                    t.addEventListener("click", function(s) {
                            s.preventDefault(),
                                r.revalidateField("password"),
                                r.validate().then(function(r) {
                                    var reset_data = {
                                        password: $("#password").val(),
                                        password_confirmation: $("#pc").val(),
                                        token: $("#token").val()
                                    };
                                    t.setAttribute("data-kt-indicator", "on"), (t.disabled = !0);
                                    if (r == "Valid") {
                                        $.ajax({
                                            type: "POST",
                                            url: "{{route('user.auth.reset')}}",
                                            data: reset_data,
                                            dataType: 'json',
                                            success: function(response) {
                                                if (response.alert == "success") {
                                                    setTimeout(function() {
                                                        t.removeAttribute("data-kt-indicator"),
                                                            (t.disabled = !1),
                                                            Swal.fire({
                                                                text: response.message,
                                                                icon: "success",
                                                                buttonsStyling: !1,
                                                                confirmButtonText: "Ok, got it!",
                                                                customClass: {
                                                                    confirmButton: "btn btn-primary"
                                                                }
                                                            }).then(function(t) {
                                                                t.isConfirmed && (e.reset());
                                                            }).then(function() {
                                                                location.href = '../../' + response.callback;
                                                            });
                                                    }, 2e3);
                                                } else {
                                                    setTimeout(function() {
                                                        t.removeAttribute("data-kt-indicator"),
                                                            (t.disabled = !1),
                                                            Swal.fire({
                                                                text: response.message,
                                                                icon: "error",
                                                                buttonsStyling: !1,
                                                                confirmButtonText: "Ok, got it!",
                                                                customClass: {
                                                                    confirmButton: "btn btn-primary"
                                                                }
                                                            }).then(function(t) {
                                                                // 
                                                            });
                                                    }, 2e3);
                                                }
                                            }
                                        });
                                    } else {
                                        t.removeAttribute("data-kt-indicator"), (t.disabled = !1);
                                        Swal.fire({
                                            text: "Sorry, looks like there are some errors detected, please try again.",
                                            icon: "error",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            },
                                        });
                                    }
                                    "Valid" == r
                                        ?
                                        (t.setAttribute("data-kt-indicator", "on"),
                                            (t.disabled = !0),
                                            setTimeout(function() {
                                                t.removeAttribute("data-kt-indicator"),
                                                    (t.disabled = !1),
                                                    Swal.fire({
                                                        text: "You have successfully reset your password!",
                                                        icon: "success",
                                                        buttonsStyling: !1,
                                                        confirmButtonText: "Ok, got it!",
                                                        customClass: {
                                                            confirmButton: "btn btn-primary"
                                                        }
                                                    }).then(
                                                        function(t) {
                                                            t.isConfirmed && ((e.querySelector('[name="password"]').value = ""), (e.querySelector('[name="password_confirmation"]').value = ""), o.reset());
                                                        }
                                                    );
                                            }, 1500)) :
                                        Swal.fire({
                                            text: "Sorry, looks like there are some errors detected, please try again.",
                                            icon: "error",
                                            buttonsStyling: !1,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            },
                                        });
                                });
                        }),
                        e.querySelector('input[name="password"]').addEventListener("input", function() {
                            this.value.length > 0 && r.updateFieldStatus("password", "NotValidated");
                        });
                },
            };
        })();
        KTUtil.onDOMContentLoaded(function() {
            KTPasswordResetNewPassword.init();
        });
    </script>
    @endsection
</x-auth-layout>