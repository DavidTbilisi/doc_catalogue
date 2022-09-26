describe('empty spec', () => {

    const login = ()=>{
        cy.visit('/')
        cy.contains('Login').click()
        cy.get('#email').type("admin@archive.gov.ge")
        cy.get('#password').type("123456789")
        cy.contains('Log in').click()
    }


    beforeEach(()=>{
        login()
        cy.get('#dropdownMenuButton1').click()
        cy.contains("ობიექ").click()
    })

  it('add Type (Table)', () => {
    cy.get('.add-button > div').as("addBtn")
    cy.get("@addBtn").click()
    cy.get("#typename").type("სახელი")
    cy.get('.btn-success').as("addField").click()
    cy.get("#field1").type("პირველი ველი{TAB}მეორე ველი")
    // cy.get("#field2").type("მეორე ველი")
    cy.get(".btn-primary").click()
  })
})
