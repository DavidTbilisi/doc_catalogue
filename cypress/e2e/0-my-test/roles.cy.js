/// <reference types="cypress" />


import {faker} from "@faker-js/faker"
let roleAlias = "";



describe('Roles', () => {
    const login = ()=>{
        cy.visit('/')
        cy.contains('Login').click()
        cy.get('#email').type("admin@archive.gov.ge")
        cy.get('#password').type("123456789")
        cy.contains('Log in').click()
    }
    const perms = [
        "Add Object",
        "View Object",
        "Edit Object",
        "Delete Object",
        "Add Document",
        "View Document",
        "Delete Document",
    ];

    beforeEach(()=>{
        login()
        cy.get('#dropdownMenuButton1').click()
        cy.contains("ადმინისტ").click()
        cy.get('.add-button > div').as("addBtn")
    })

    it("Role Add", () => {
        let role = {
            alias: faker.internet.domainName(),
            description: faker.lorem.lines(2) // სიტყვების რაოდენობა შეზღუდულია?
        }


        roleAlias = role.alias
        cy.log(roleAlias)

        // add role
        cy.get("@addBtn").click()
        cy.get('#alias').type(role.alias)
        cy.get('#description').type(role.description)
        cy.get(".btn.btn-success").click() // save
        cy.get('.alert').should("contain", "წარმატებით")
    })


    it("Role edit", ()=>{
        let roleNew = {
            alias: faker.internet.domainName(),
            description: faker.lorem.lines(2)
        }


        cy.log(roleAlias)
        cy.contains(roleAlias[0].toUpperCase() + roleAlias.substring(1)).click();
        cy.get('#alias').clear().type(roleNew.alias)
        roleAlias = roleNew.alias

        cy.get('#description').clear().type(roleNew.description)
        cy.get(".btn.btn-success").click() // save
    })


    it("Role delete", ()=>{
        cy.log(roleAlias)
        cy.contains(roleAlias[0].toUpperCase() + roleAlias.substring(1)).click();
        cy.get(".btn.btn-danger").click() // delete
    })
})
