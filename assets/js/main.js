document.addEventListener("DOMContentLoaded", () => {
  // Custom cursor
  const cursor = document.querySelector(".cursor")
  const cursorFollower = document.querySelector(".cursor-follower")

  document.addEventListener("mousemove", (e) => {
    cursor.style.left = e.clientX + "px"
    cursor.style.top = e.clientY + "px"

    setTimeout(() => {
      cursorFollower.style.left = e.clientX + "px"
      cursorFollower.style.top = e.clientY + "px"
    }, 100)
  })

  document.addEventListener("mousedown", () => {
    cursor.style.transform = "translate(-50%, -50%) scale(0.8)"
    cursorFollower.style.transform = "translate(-50%, -50%) scale(0.8)"
  })

  document.addEventListener("mouseup", () => {
    cursor.style.transform = "translate(-50%, -50%) scale(1)"
    cursorFollower.style.transform = "translate(-50%, -50%) scale(1)"
  })

  // Hover effect for links and buttons
  const links = document.querySelectorAll("a, button, .product-card, .category-card")
  links.forEach((link) => {
    link.addEventListener("mouseenter", () => {
      cursorFollower.style.width = "50px"
      cursorFollower.style.height = "50px"
      cursor.style.opacity = "0.5"
    })

    link.addEventListener("mouseleave", () => {
      cursorFollower.style.width = "30px"
      cursorFollower.style.height = "30px"
      cursor.style.opacity = "1"
    })
  })

  // Header scroll effect
  const header = document.querySelector(".main-header")
  window.addEventListener("scroll", () => {
    if (window.scrollY > 50) {
      header.classList.add("scrolled")
    } else {
      header.classList.remove("scrolled")
    }
  })

  // Toggle mobile menu
  const mobileMenuToggle = document.querySelector(".mobile-menu-toggle")
  const mobileMenu = document.querySelector(".mobile-menu")

  if (mobileMenuToggle && mobileMenu) {
    mobileMenuToggle.addEventListener("click", function () {
      this.classList.toggle("active")
      mobileMenu.classList.toggle("active")
    })
  }

  // Product image gallery
  const mainImage = document.getElementById("main-image")
  const thumbnails = document.querySelectorAll(".product-thumbnail")

  if (mainImage && thumbnails.length > 0) {
    thumbnails.forEach((thumbnail) => {
      thumbnail.addEventListener("click", function () {
        // Remove active class from all thumbnails
        thumbnails.forEach((t) => t.classList.remove("active"))

        // Add active class to clicked thumbnail
        this.classList.add("active")

        // Update main image
        const imgSrc = this.querySelector("img").getAttribute("src")
        mainImage.setAttribute("src", imgSrc)

        // Animate main image
        mainImage.style.opacity = "0"
        setTimeout(() => {
          mainImage.style.opacity = "1"
        }, 300)
      })
    })
  }

  // Quantity selectors
  const quantityInputs = document.querySelectorAll(".quantity-input")

  quantityInputs.forEach((input) => {
    const decreaseBtn = input.previousElementSibling
    const increaseBtn = input.nextElementSibling

    if (decreaseBtn && increaseBtn) {
      decreaseBtn.addEventListener("click", () => {
        const value = Number.parseInt(input.value)
        if (value > 1) {
          input.value = value - 1
        }
      })

      increaseBtn.addEventListener("click", () => {
        const value = Number.parseInt(input.value)
        const max = Number.parseInt(input.getAttribute("max") || 99)
        if (value < max) {
          input.value = value + 1
        }
      })
    }
  })

  // Product tabs
  const tabBtns = document.querySelectorAll(".tab-btn")
  const tabContents = document.querySelectorAll(".tab-content")

  if (tabBtns.length > 0 && tabContents.length > 0) {
    tabBtns.forEach((btn) => {
      btn.addEventListener("click", () => {
        // Remove active class from all buttons and contents
        tabBtns.forEach((b) => b.classList.remove("active"))
        tabContents.forEach((c) => c.classList.remove("active"))

        // Add active class to clicked button
        btn.classList.add("active")

        // Show corresponding content
        const target = btn.getAttribute("data-target")
        document.getElementById(target).classList.add("active")
      })
    })
  }

  // Testimonial slider
  const testimonialDots = document.querySelectorAll(".testimonial-dot")
  const testimonials = document.querySelectorAll(".testimonial")

  if (testimonialDots.length > 0 && testimonials.length > 0) {
    testimonialDots.forEach((dot, index) => {
      dot.addEventListener("click", () => {
        // Remove active class from all dots and testimonials
        testimonialDots.forEach((d) => d.classList.remove("active"))
        testimonials.forEach((t) => (t.style.display = "none"))

        // Add active class to clicked dot
        dot.classList.add("active")

        // Show corresponding testimonial
        testimonials[index].style.display = "block"
      })
    })
  }

  // Smooth scroll
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault()

      document.querySelector(this.getAttribute("href")).scrollIntoView({
        behavior: "smooth",
      })
    })
  })

  // Image preview for file inputs
  const fileInputs = document.querySelectorAll('input[type="file"]')

  fileInputs.forEach((input) => {
    input.addEventListener("change", function () {
      const file = this.files[0]
      if (file) {
        const reader = new FileReader()
        const previewElement = document.querySelector(`#${this.id}-preview`)

        if (previewElement) {
          reader.onload = (e) => {
            previewElement.src = e.target.result
          }

          reader.readAsDataURL(file)
        }
      }
    })
  })

  // Payment method selection
  const paymentMethods = document.querySelectorAll(".payment-method")

  if (paymentMethods.length > 0) {
    paymentMethods.forEach((method) => {
      method.addEventListener("click", () => {
        // Remove active class from all methods
        paymentMethods.forEach((m) => m.classList.remove("active"))

        // Add active class to clicked method
        method.classList.add("active")

        // Select the radio button
        const radio = method.querySelector('input[type="radio"]')
        if (radio) {
          radio.checked = true
        }
      })
    })
  }

  // Parallax effect
  const parallaxElements = document.querySelectorAll(".parallax")

  if (parallaxElements.length > 0) {
    window.addEventListener("scroll", () => {
      const scrollY = window.scrollY

      parallaxElements.forEach((element) => {
        const speed = element.getAttribute("data-speed") || 0.1
        element.style.transform = `translateY(${scrollY * speed}px)`
      })
    })
  }

  // AOS-like scroll animations
  const animateElements = document.querySelectorAll(".animate")

  if (animateElements.length > 0) {
    const checkInView = () => {
      animateElements.forEach((element) => {
        const elementTop = element.getBoundingClientRect().top
        const elementBottom = element.getBoundingClientRect().bottom
        const windowHeight = window.innerHeight

        if (elementTop < windowHeight - 100 && elementBottom > 0) {
          element.classList.add("in-view")
        }
      })
    }

    window.addEventListener("scroll", checkInView)
    checkInView() // Check on load
  }
})
