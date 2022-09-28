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


    it.skip("Add IO", () => {
        addIO()
        addIO()
        addIO()
    })

    it("Add sub-io", () => {

        cy.get(':nth-child(1) > :nth-child(5) > .btn-success > .material-icons').click();
        cy.location().then((loc) => {
            cy.log(loc.pathname)
            io_id = loc.pathname.at(-1)
        })

        // addIO()
    })

    it("Edit IO", () => {

        cy.log(io_id)


    })


})
