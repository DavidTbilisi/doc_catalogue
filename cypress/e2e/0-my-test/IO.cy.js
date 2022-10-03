/// <reference types="cypress" />

import {faker} from "@faker-js/faker/locale/ge";
let io_id;
describe('IO Manipulations', () => {

    const login = ()=>{
        cy.visit('/')
        cy.contains('Login').click()
        cy.get('#email').type("admin@archive.gov.ge")
        cy.get('#password').type("123456789")
        cy.contains('Log in').click()
    }

    const randomInt = (min, max) => {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }

    const addIO = () => {
        cy.get('.add-button > div > a').as("addBtn").click()
        cy.get('#prefix').type(randomInt(1, 100))
        cy.get('#identifier').type(randomInt(1, 100));
        cy.get('#suffix').type(randomInt(1, 100));
        cy.get('#type').select('დონე ორი');
        cy.get('input[type=date]').type(`2022-0${randomInt(1,9)}-0${randomInt(1, 9)}`);
        cy.get('input[type=number]').type(randomInt(2,100)); // ვალიდაცია რიცხვითი სიმბოლოების მაქსიმალური რაოდენობა
        cy.get('#teqsti').type(randomInt(1, 100));
        cy.get('.btn-success').click();
    }

    beforeEach(()=>{
        login()

    })


    it("Add IO", () => {
        // Todo: add pagination to the IO page
        for (let i = 0; i < 20; i++) {
            addIO()
        }
    })

    it.skip("Add sub-io", () => {

        cy.get(':nth-child(1) > :nth-child(5) > .btn-success > .material-icons').click();
        cy.location().then((loc) => {
            cy.log(loc.pathname)
            io_id = loc.pathname.at(-1)
        })

        // addIO()
    })

    it.skip("Edit IO", () => {
        cy.visit("admin/io/edit/" + io_id)
        cy.get('#prefix').clear().type("edited_"+randomInt(1, 100))
        cy.get('#identifier').clear().type(randomInt(1, 100));
        cy.get('#suffix').clear().type("edited_"+randomInt(1, 100));
        cy.get('.btn-success').click();
        cy.get('.btn-danger').click();
        cy.visit("admin/io/show/" + io_id)
    })

    it.skip("Edit Data", () => {
        cy.visit("admin/io/show/" + io_id)
        cy.contains("მონაცემები").click()
        cy.get('input[type=date]').clear().type(`2022-0${randomInt(1,9)}-0${randomInt(1, 9)}`);
        cy.get('input[type=number]').clear().type(randomInt(2,100)); // ვალიდაცია რიცხვითი სიმბოლოების მაქსიმალური რაოდენობა
        cy.get('#teqsti').clear().type(randomInt(1, 100));
        cy.get('.btn-success').click();
        cy.get('.alert').should("contain", "წარმატებით"); // TODO: უკან გადასვლა არ მუშაობს
    })


    it.skip("Permissions", () => {
        cy.visit("admin/io/permissions/" + io_id)
        // todo: after changing permissions, it gives me permission to change all permissions
        cy.get("#form_Editor #viewDocumentEditor").click()
        cy.get("#form_Editor .btn-success").click()
    })

    it.skip("Delete IO", () => {
        cy.get("#row-0 form button").click()
        cy.get('.alert').should("contain", "არ არის ცარიელი"); //
        cy.get("#row-1 form button").click()
        cy.get('.alert').should("contain", "წარმატებით"); //
    })
})
