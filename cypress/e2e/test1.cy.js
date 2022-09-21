describe('empty spec', () => {
    const faker = require("faker");
    beforeEach(() => {

    })


    const login = ()=>{
        cy.visit('/')
        cy.contains('Login').click()
        cy.get('#email').type("admin@archive.gov.ge")
        cy.get('#password').type("123456789")
        cy.contains('Log in').click()

    }

  it('Register  if data is correct', () => {
    cy.visit('/')
    cy.contains('Register').click()
    cy.get('#name').type("David")
    cy.get('#email').type(faker.email())
    cy.get('#password').type("123456789")
    cy.get('#password_confirmation').type("123456789")
    cy.contains('Register').click()
  })

    it("Login if data is correct", login )

    it("Add User", () => {
        login();
        cy.get('#dropdownMenuButton1').click()
        cy.contains("მომხმარებ").click()
        cy.get('.add-button > div').click();
        cy.get("#name").type("newUser")
        cy.get("#email").type("newUser@gmail.com")
        cy.get("#password").type("123456789")
        cy.get(".btn-success").click()
        cy.get(".alert").should("contain", "წარმატებით")

    })



})
