/// <reference types="cypress" />

import {faker} from "@faker-js/faker/locale/ge";

describe('IO Manipulations', () => {

    const login = ()=>{
        cy.visit('/')
        cy.contains('Login').click()
        cy.get('#email').type("admin@archive.gov.ge")
        cy.get('#password').type("123456789")
        cy.contains('Log in').click()
    }

    beforeEach(()=>{
        login()

    })


    it("Add IO", () => {
        cy.get('.add-button > div > a').as("addBtn").click()
        cy.get('#prefix').type("1");
        cy.get('#identifier').type(1);
        cy.get('#suffix').type(1);
        cy.get('#type').select('დონე ორი');
        cy.get('input[type=date]').type("2022-01-01");
        cy.get('input[type=number]').type(Math.floor(Math.random(0)*100)); // ვალიდაცია რიცხვითი სიმბოლოების მაქსიმალური რაოდენობა
        cy.get('#teqsti').type(Math.floor(Math.random(0)*100));
        cy.get('.btn-success').click();
    })

    it.skip("Edit IO", () => {


    })



})
