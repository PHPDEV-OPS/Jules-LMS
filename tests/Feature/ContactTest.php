<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test that the contact page loads successfully.
     */
    public function test_contact_page_loads()
    {
        $response = $this->get('/contact');
        
        $response->assertStatus(200);
        $response->assertSee('Get in Touch');
        $response->assertSee('Send us a Message');
        $response->assertSee('Contact Information');
    }

    /**
     * Test contact form validation.
     */
    public function test_contact_form_validation()
    {
        $response = $this->post('/contact', []);
        
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
    }

    /**
     * Test successful contact form submission.
     */
    public function test_contact_form_submission_success()
    {
        $contactData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Course Inquiry',
            'message' => 'I am interested in learning more about your cooking classes.',
        ];

        $response = $this->post('/contact', $contactData);
        
        $response->assertStatus(302);
        $response->assertRedirect('/contact');
        $response->assertSessionHas('success', 'Thank you for your message! We\'ll get back to you soon.');
    }

    /**
     * Test contact form with invalid email.
     */
    public function test_contact_form_invalid_email()
    {
        $contactData = [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'subject' => 'Course Inquiry',
            'message' => 'Test message',
        ];

        $response = $this->post('/contact', $contactData);
        
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test contact form with message too long.
     */
    public function test_contact_form_message_too_long()
    {
        $contactData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Course Inquiry',
            'message' => str_repeat('A', 2001), // 2001 characters, over the limit
        ];

        $response = $this->post('/contact', $contactData);
        
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['message']);
    }
}