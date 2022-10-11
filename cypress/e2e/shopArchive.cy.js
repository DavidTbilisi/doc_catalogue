describe('empty spec', () => {
  it('passes', () => {
    cy.visit('http://shop.archives.gov.ge/admin')

    cy.get('#identity').type("admin@a.ge")
    // cy.get(':nth-child(2) > [style="padding-left: 40px;"] > input').type(111)
    // cy.get("div > input").click()
  })
})