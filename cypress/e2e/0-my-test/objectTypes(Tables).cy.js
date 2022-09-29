import {faker} from "@faker-js/faker/locale/ge";
let tableName = faker.name.lastName();

describe('Object Types Manipulation', () => {

    const login = () => {
        cy.visit('/')
        cy.contains('Login').click()
        cy.get('#email').type("admin@archive.gov.ge")
        cy.get('#password').type("123456789")
        cy.contains('Log in').click()
    }

    const addType = (tablename, fields) => {
        cy.get('.add-button > div').as("addBtn")
        cy.get("@addBtn").click()
        cy.get("#typename").type(tablename)
        for( let i = 0; i < fields.length; i++ ) {
            if (i != fields.length-1) {
                cy.get('.btn-success').as("addField").click()
            }
            cy.get(`#field${i+1}`).type(fields[i].name)
            cy.get(`#field${i+1}`).parents(".col-8").siblings().find("select").select(fields[i].type)

        }
        cy.get(".btn-primary").click()
    }


    beforeEach(()=>{
        login()
        cy.get('#dropdownMenuButton1').click()
        cy.contains("ობიექ").click()
    })

  it('add Type (Table)', () => {
      addType(tableName, [
          {name:"პირველი ველი", type:"Date"},
          {name:"მეორე ველი", type:"Number"},
          {name:"მესამე ველი", type:"longText"}
      ])

      addType("დონე ორი", [
          {name:"დრო", type: "Date"},
          {name:"რიცხვი", type:'Number'},
          {name:'ტექსტი', type:"longText"}
      ])
      addType("დონე სამი", [
          {name:"დრო", type: "Date"},
          {name:"რიცხვი", type:'Number'},
          {name:'ტექსტი', type:"longText"}
      ])
      addType("დონე ოთხი", [
          {name:"დრო", type: "Date"},
          {name:"რიცხვი", type:'Number'},
          {name:'ტექსტი', type:"longText"}
      ])
  })


    it('edit Type (Table)', () => {
        cy.contains(tableName).click()
        cy.get('div > .material-icons').as("addField").click()
        cy.get("#newColumn").type("მესამე ველი")
        cy.get(".btn-success").click()
    })

    it('delete Type (Table)', () => {
        cy.contains(tableName).parents("tr").find('form').submit()
    })

})
