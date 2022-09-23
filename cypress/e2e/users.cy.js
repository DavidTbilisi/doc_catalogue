/// <reference types="cypress" />

import {faker} from "@faker-js/faker"

describe('Users', () => {



    const login = ()=>{
        cy.visit('/')
        cy.contains('Login').click()
        cy.get('#email').type("admin@archive.gov.ge")
        cy.get('#password').type("123456789")
        cy.contains('Log in').click()
    }



  // it('Register  if data is correct', () => {
  //   cy.visit('/')
  //   cy.contains('Register').click()
  //   cy.get('#name').type("David")
  //   cy.get('#email').type(faker.internet.email())
  //   cy.get('#password').type("123456789")
  //   cy.get('#password_confirmation').type("123456789")
  //   cy.contains('Register').click()
  // })

    // it("Login if data is correct", login )

    it("User Manipulations", () => {
        login();
        const perms = [
                         "Add Object",
                         "View Object",
                         "Edit Object",
                         "Delete Object",
                         "Add Document",
                         "View Document",
                         "Delete Document",
                    ];

        let email = faker.internet.email();
        const user = {
            email: email,
            name: email.split('@')[0]
        }
        cy.log(user);
        // Add user
        cy.get('#dropdownMenuButton1').click()
        cy.contains("მომხმარებ").click()
        cy.get('.add-button > div').click();
        cy.get("#name").type(user.name)
        cy.get("#email").type(user.email)
        cy.get("#password").type("123456789")
        cy.get(".btn-success").click()
        cy.get(".alert").should("contain", "წარმატებით")

        // rename User
        cy.contains(user.name).click()

        user.email = faker.internet.email()
        user.name = user.email.split('@')[0]

        cy.get("#name").clear().type(user.name)
        cy.get("#email").clear().type(user.email)
        cy.get("#group_id").select("admin")
        cy.get('.btn.btn-success').as("saveBtn").click()

        // add Permissions to User
        cy.contains(user.name).click()
        perms.forEach(el => cy.contains(el).click())
        cy.get('@saveBtn').click()

        cy.contains(user.name).parents('tr').within(()=>{
            perms.forEach(el => cy.contains(el).should("be.visible"))
        })

        // Delete User
        cy.contains(user.name).click()
        cy.get(`.btn.btn-danger`).as("deleteBtn")
        cy.get("@deleteBtn").click()

    })



})
